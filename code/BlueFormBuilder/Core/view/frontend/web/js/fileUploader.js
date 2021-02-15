define([
    'jquery',
    'mage/template',
    'mage/translate',
    'jquery/file-uploader',
    'Magezon_Core/js/magnific.min'
    ], function ($, mageTemplate) {
        'use strict';

    $.widget('bfb.mediaUploader', {
        values: [],
        images: [],
        randomId: '',
        startIndex: 0,
        rowTemplate: '<div id="<%- data.id %>" class="bfb-file-row"><div class="bfb-file-row-inner"><span class="bfb-file-info"><% if (data.src) { %><a href="<%- data.src %>"><img src="<%- data.src %>"/></a><% } else { %><span class="fa fa-file"></span><% } %><span class="bfb-file-info-name"><%- data.name %> <% if (data.size) { %>(<%- data.size %>)<% } %></span></span> <i class="far mgz-fa-times-circle bfb-file-delete" data-id="<%- data.fileId %>"></i></div></div>',

        /**
         *
         * @private
         */
         _create: function () {

            this.values     = [];
            this.images     = [];
            this.randomId   = '';
            this.startIndex = 0;
            
            var self     = this;
            var fileTmpl = mageTemplate(this.rowTemplate);
            var fileList = this.element.find('.bfb-file-list');

            $.each(this.options.values, function (index, file) {
                var data = [];
                data['fileId'] = file['html_id'];
                data['name']   = file['name'];
                data['size']   = self.byteConvert(file['size']);
                var tmpl = fileTmpl({
                    data: data
                });
                $(tmpl).data('image', data).appendTo(fileList);
                self.values[file['html_id']] = file['file'];
            });

            $(document).on('click', '#' + this.options.id + ' .bfb-file-delete', function(event) {
                $(this).parents('.bfb-file-row').remove();

                var id = $(this).data('id');

                self.deleteFile(self.values[id]);

                delete self.values[id];

                self.updateValues();
            });

            this.element.on('click', '.bfb-file-insert', function() {
                self.element.find('input[type=file]')[0].click();
            });

            this.element.on('addItem', function (event, data) {
                data['fileId'] = data.rand;
                data['src']    = self.images[data.rand];

                var tmpl = fileTmpl({
                    data: data
                });

                $(tmpl).data('image', data).appendTo(fileList);

                self.values[data['fileId']] = data['file'];

                self.updateValues();
            });

            var formKey = this.element.parents('.form.bfb-form').find('input[name=bfb_form_key]').val();

            this.element.find('input[type=file]').fileupload({
                dataType: 'json',
                sequentialUploads: true,
                dropZone: $(self.element).find('[data-role=drop-zone]'),

                /**
                 * @param {Object} e
                 * @param {Object} data
                 */
                 add: function (e, data) {
                    var randomId = ++self.startIndex;
                    var file     = data.files[0];
                    var reader   = new FileReader();

                    reader.addEventListener("load", function () {
                        if (reader.result && reader.result.startsWith('data:image')) {
                            self.images[randomId] = reader.result;
                        };
                    }, false);

                    if (file) {
                        reader.readAsDataURL(file);
                    }

                    if (self.options.maxFiles > 1 && self.options.maxFiles < (self.getValuesLength()+1)) {
                        alert($.mage.__('Maximum number of files exceeded.'));
                        return;
                    }

                    var fileSize;

                    $.each(data.files, function (index, file) {
                        fileSize = typeof file.size == 'undefined' ?
                        $.mage.__('We could not detect a size.') :
                        self.byteConvert(file.size);
                    });

                    self.element.addClass('loading');

                    $(this).fileupload({
                        formData: {
                            'id': $(this).attr('id'),
                            'key': formKey,
                            'rand': randomId,
                            
                        },
                    });

                    $(this).fileupload('process', data).done(function () {
                        data.submit();
                    });
                },

                /**
                 * @param {Object} e
                 * @param {Object} data
                 */
                 done: function (e, data) {
                    self.element.removeClass('loading');

                    if (data.result && !data.result.error && !data.result.error_message) {
                        if (self.options.maxFiles > 1 && self.options.maxFiles < (self.getValuesLength()+1)) {
                            alert($.mage.__('Maximum number of files exceeded.'));
                        } else {
                            var result = data.result;
                            result['size'] = self.byteConvert(result['size']);

                            self.element.trigger('addItem', data.result);
                        }
                    } else if (data.result.error_message) {
                        alert(data.result.error_message);
                    } else if (data.result.error) {
                        alert(data.result.error);
                    } else {
                        alert($.mage.__('We don\'t recognize or support this file extension type.'));
                    }

                    self._initMagnificPopup();
                },

                /**
                 * @param {Object} e
                 * @param {Object} data
                 */
                 progress: function (e, data) {
                    var progress = parseInt(data.loaded / data.total * 100, 10),
                    progressSelector = '#' + data.fileId + ' .progressbar-container .progressbar';

                    self.element.find(progressSelector).css('width', progress + '%');
                },

                /**
                 * @param {Object} e
                 * @param {Object} data
                 */
                 fail: function (e, data) {
                    var progressSelector = '#' + data.fileId;

                    self.element.find(progressSelector).removeClass('upload-progress').addClass('upload-failure')
                    .delay(2000)
                    .hide('highlight')
                    .remove();
                }
            });
},

        /**
         * Convert byte count to float KB/MB format
         *
         * @param int $bytes
         * @return string
         */
         byteConvert: function(bytes) {
            if (isNaN(bytes)) {
                return '';
            }
            var symbols = ['bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
            var exp = Math.floor(Math.log(bytes) / Math.log(2));

            if (exp < 1) {
                exp = 0;
            }
            var i = Math.floor(exp / 10);

            bytes /= Math.pow(2, 10 * i);

            if (bytes.toString().length > bytes.toFixed(2).toString().length) {
                bytes = bytes.toFixed(2);
            }

            return bytes + ' ' + symbols[i];
        },

        updateValues: function() {
            var result = '';
            var values = _.compact(this.values);
            for(var i=0; i < values.length; i++) {
                result += values[i];
                if (values[i+1]) {
                    result += ',';
                }
            }
            this.element.find('[data-role=values]').eq(0).val(result);
        },

        getValuesLength: function() {
            var length = 0;
            var values = this.values;
            for (var k in values) {
                if (values.hasOwnProperty(k)) {
                    length++;
                }
            }
            return length;
        },

        deleteFile: function(name) {
            var submitData     = {};
            submitData['file'] = name;
            submitData['key']  = this.element.parents('.form.bfb-form').find('input[name=bfb_form_key]').val();
            var deleteUrl      = this.options.deleteUrl;
            $.ajax({
                url: deleteUrl,
                data: submitData,
                type: 'post',
                dataType: 'json'
            });
        },

        _initMagnificPopup: function() {
            this.element.find('.bfb-file-info').magnificPopup({
                delegate: 'a',
                type: 'image',
                closeOnContentClick: false,
                closeBtnInside: false,
                mainClass: 'mfp-with-zoom mfp-img-mobile',
                gallery: {
                    enabled: true
                },
                zoom: {
                    enabled: true,
                    duration: 300, // don't foget to change the duration also in CSS
                    opener: function(element) {
                        return element.find('img');
                    }
                }
            });
        }
    });

    return $.bfb.mediaUploader;
});
