var appAddLinkForRID = angular.module('appAddLinkForRID', ['ui.bootstrap'])

.controller('AddLinkForRIDCtrl', function($scope, $http, $modal, $log) {
    $scope.addLinkRID = function(typeOfLink) {
        var modalInstance = $modal.open({
            animation: $scope.animationsEnabled,
            templateUrl: 'addLinkRIDContent.html',
            controller: 'ModalInstanceLinkRIDCtrl',
            resolve: {
                params: function() {
                    return {'selection':$scope.form.dynamicFields[typeOfLink], 'allRID':$scope.allRID};
                },
            }
        });

        modalInstance.result.then(function(paramsModalInstanceLink) {
         $scope.form.dynamicFields[typeOfLink] = paramsModalInstanceLink['selection'];
         console.log ($scope.form.dynamicFields[typeOfLink]);

        }, function() {
            $log.info('Modal dismissed at: ' + new Date());
        });

    }

})

.controller('ModalInstanceLinkRIDCtrl', function($scope, $modalInstance, params, helper) {
  $scope.paramsModalInstanceLink = JSON.parse( JSON.stringify( params) );
  $scope.toggleSelection = function toggleSelection(v) {
    //var idx = $scope.paramsModalInstanceLink.selection.indexOf(i);
    var idx = helper.findWithAttr($scope.paramsModalInstanceLink.selection, 'idLinkRid', v.id)
    if (idx > -1) {
      $scope.paramsModalInstanceLink.selection.splice(idx, 1);
    }
    else {
      $scope.paramsModalInstanceLink.selection.push({'idLinkRid':v.id});
    }
  };
  
  $scope.isSelection = function (v) {
    return helper.findWithAttr($scope.paramsModalInstanceLink.selection, 'idLinkRid', v.id) > -1;
  }

  $scope.ok = function() {
    $modalInstance.close({'selection':$scope.paramsModalInstanceLink['selection']});
  };

  $scope.cancel = function() {
    $modalInstance.dismiss('cancel');
  };
});


