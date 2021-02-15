define([
	'jquery',
	'angular',
	'Magezon_Core/js/ion.rangeSlider.min'
], function($, angular) {

	var directive = function(magezonBuilderUrl, elementManager, $compile, $templateRequest) {
		return {
      		replace: true,
			templateUrl: function(elem) {
				return magezonBuilderUrl.getTemplateUrl(elem, 'BlueFormBuilder_Core/js/templates/builder/field.html');
			},
			controller: function($scope,  $controller) {
				var parent = $controller('listController', {$scope: $scope});
				angular.extend(this, parent);

				$scope.getFieldClasses = function() {
					var element = $scope.element;
					var classes = 'bfb-element';
					
					classes += ' mgz-element-' + element.type;

		            if ($labelPosition = element.label_position) {
			            classes += ' bfb-element-label-' + $labelPosition;
			        }

			        if ($labelAlignment = element.label_alignment) {
			            classes += ' bfb-element-label-align-' + $labelAlignment;
			        }

			        if (element.required) {
			            classes += ' required';
			        }

			        if (element.show_icon && element.icon) {
						classes += ' bfb-element-icon-' + element.icon_position;
			        }

			        if (element.hidden) {
			            classes += ' bfb-element-hidden';
			        }

		            return classes;
				}

				$scope.range = function(min, max, step) {
				    step = step || 1;
				    var input = [];
				    for (var i = min; i <= max; i += step) {
				        input.push(i);
				    }
				    return input;
				};
			},
			link: function(scope, element) {
				var initIonSlider = function() {
					$(element).find('.ionslider').each(function(index, el) {
						var sliderSelector = $(this);
						setTimeout(function() {
							$(sliderSelector).ionRangeSlider();
							var slider = $(sliderSelector).data("ionRangeSlider");
				      		slider.update({
								min: $(sliderSelector).data('min'),
								max: $(sliderSelector).data('max'),
								from: $(sliderSelector).data('default_value'),
								step: $(sliderSelector).data('step'),
								prefix: $(sliderSelector).data('prefix'),
								postfix: $(sliderSelector).data('postfix'),
								grid: true
							});
						}, 1000);
			      	});
				}
				var builderElement = elementManager.getElement(scope.element.type);
				if (builderElement.elementTmpl) {
					$templateRequest(magezonBuilderUrl.getViewFileUrl(builderElement.elementTmpl)).then(function(html) {
						var template = angular.element(html);
						var newHtml  = $compile(template)(scope);
				      	element.find('.mgz-component-element-tmpl').html(newHtml);
				      	initIonSlider();
				   	});
				}
				scope.$watch('element', function() {
				    initIonSlider();
				}, true);
			},
			controllerAs: 'mgz'
		}
	}

	return directive;
});