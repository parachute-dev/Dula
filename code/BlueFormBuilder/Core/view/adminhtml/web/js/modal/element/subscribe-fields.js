define([
	'jquery',
	'angular'
], function($, angular) {

	return {
		controller: function($scope, $rootScope, magezonBuilderService, elementManager) {
			var elemens = angular.copy($rootScope.profile.elements);
			var fields  = magezonBuilderService.parseOptions(elemens, 'elements');
			var options = [];
			angular.forEach(fields.cacheOptions.plain, function(row) {
				var builderElement = elementManager.getElement(row.type);
                if (builderElement.type == 'bfb_email') {
                	options.push({
                		value: row.id,
                		label: row.label
                	});
                }
			});
			$scope.to.options = options;
		}
	}
});