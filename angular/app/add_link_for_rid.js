var appAddLinkForRID = angular.module('appAddLinkForRID', ['ui.bootstrap'])

.controller('AddLinkForRIDCtrl', function($scope, $http, $modal, $log) {
    $scope.addLinkRID = function(typeOfLink) {
        var modalInstance = $modal.open({
            animation: $scope.animationsEnabled,
            templateUrl: 'addLinkRIDContent.html',
            controller: 'ModalInstanceLinkRIDCtrl',
            resolve: {
                params: function() {
                    return {'selection':$scope.form.staticFields[typeOfLink], 'allRID':$scope.allRID};
                },
            }
        });

        modalInstance.result.then(function(paramsModalInstanceLink) {
         $scope.form.staticFields[typeOfLink] = paramsModalInstanceLink['selection'];
         console.log ($scope.form.staticFields[typeOfLink]);

        }, function() {
            $log.info('Modal dismissed at: ' + new Date());
        });

    }

})

.controller('ModalInstanceLinkRIDCtrl', function($scope, $modalInstance, params) {
  $scope.paramsModalInstanceLink = JSON.parse( JSON.stringify( params) );
  $scope.toggleSelection = function toggleSelection(i) {
    var idx = $scope.paramsModalInstanceLink.selection.indexOf(i);
    if (idx > -1) {
      $scope.paramsModalInstanceLink.selection.splice(idx, 1);
    }
    else {
      $scope.paramsModalInstanceLink.selection.push(i);
    }
  };
  
  $scope.ok = function() {
    $modalInstance.close({'selection':$scope.paramsModalInstanceLink['selection']});
  };

  $scope.cancel = function() {
    $modalInstance.dismiss('cancel');
  };
});


