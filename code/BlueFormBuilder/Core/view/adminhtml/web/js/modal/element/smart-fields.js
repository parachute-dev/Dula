define([
	'jquery',
	'angular'
], function($, angular) {

	return {
		controller: function($scope, magezonBuilderService) {
			$scope.variables = [];
			$scope.activeTabIndex = 0;
			magezonBuilderService.getBuilderConfig('profile.prefinedVariables', function(result) {
				$scope.variables = result;
			});

			$scope.$watch('filterInputValue', function(value) {
				if (typeof value !== 'undefined') {
					$scope.filterOptionsFocus = true;
					var value   = value.trim().toLowerCase();
					var options = $scope.getFullOptions();
					$scope.filterOptions = $scope._getFilteredArray(options, value);
				}
			});

			$scope.getFullOptions = function() {
	            var options = [];
	            _.each($scope.variables, function (row) {
	                _.each(row.options, function (option) {
	                    options.push(option);
	                });
	            });
	            return options;
	        }

	        $scope._getFilteredArray = function (list, value) {
	            var i = 0,
	                array = [],
	                curOption;

	            for (i; i < list.length; i++) {
	                curOption = list[i].label.toLowerCase();
	                if (curOption.indexOf(value) > -1) {
	                    array.push(list[i]);
	                }
	            }
	            return array;
	        }

			$scope.activeTab = function(option, index, selector) {
				$scope.activeTabIndex     = index;
				$scope.filterOptionsFocus = false;
	        }

	        $scope.addOptionSelected = function(option) {
	        	if (!$scope.model[$scope.options.key]) $scope.model[$scope.options.key] = '';
				$scope.model[$scope.options.key] = $scope.model[$scope.options.key] += option.value;
	        }
		}
	}
});