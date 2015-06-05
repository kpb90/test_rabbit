var appControllers = angular.module('appControllers', ['ui.bootstrap']);

appControllers.controller('AppCtrl', function($scope, $parse, $http, $location) {

(function () {
	$scope.$watch(function() { return angular.toJson($scope.basket);}, function() {
		if (typeof $scope.basket != 'undefined'){
			$scope.get_common_price_and_count();
		}
	});
	$scope.type_basket = $location.absUrl().indexOf('carlsberg')!=-1 ? 'carlsberg' : '';
	$scope.text= {comment : '', cost_center:'', type_of_expenditure:''};
	$http.get('/index.php?get_cart=1&type='+$scope.type_basket).success(function (data,status) {
			$scope.basket =data['basket'];
			$scope.currency =data['currency'];
			console.log ($scope.basket);
		}).error(function (data,status){
		});
	})();

	$scope.remove_item = function (cart_item_id) {
		$http.get('/index.php?remove_cart=1&type='+$scope.type_basket+'&cart_item_id='+$scope.basket[cart_item_id].cart_item_id).success(function (data,status) {
			delete $scope.basket[cart_item_id];
		}).error(function (data,status){
			console.log('Ошибка при удалении');
		});
	}

	$scope.remove_size = function (cart_item_id, index) {
		$http.get('/index.php?remove_size_from_cart=1&type='+$scope.type_basket+'&cart_item_id='+$scope.basket[cart_item_id].cart_item_id+'&remove_size_from_cart='+$scope.basket[cart_item_id].select_size[index]).success(function (data,status) {
			console.log('remove_size');
			$scope.basket[cart_item_id].select_size.splice(index, 1);
			$scope.basket[cart_item_id].quantity.splice(index, 1);
			$scope.basket[cart_item_id].common_row_price.splice(index, 1);
		}).error(function (data,status){
			console.log('Ошибка при удалении');
		});
	}

	$scope.add_size = function (cart_item_id) {
			var new_size = $scope.basket[cart_item_id].new_size,
				index = $scope.basket[cart_item_id].select_size.indexOf(new_size);
		if (new_size==-1) return;
		if ($scope.basket[cart_item_id].select_size.length==1&&!$scope.basket[cart_item_id].select_size[0]) {
			$http.get('/index.php?update_size=1&type='+$scope.type_basket+'&cart_item_id='+$scope.basket[cart_item_id].cart_item_id+'&new_size='+new_size+'&new_quantity=1&old_size=').success(function (data,status) {
				$scope.basket[cart_item_id].select_size[0] = new_size;
			}).error(function (data,status){
				console.log('Ошибка при добавлении нового размера');
			});
		} else if (index == -1) {
			$http.get('/index.php?add_size=1&type='+$scope.type_basket+'&cart_item_id='+$scope.basket[cart_item_id].cart_item_id+'&new_size='+new_size+'&new_quantity=1').success(function (data,status) {
				$scope.basket[cart_item_id].select_size.push(new_size);
				$scope.basket[cart_item_id].quantity.push(1);
				$scope.basket[cart_item_id].common_row_price.push($scope.basket[cart_item_id].price);
			}).error(function (data,status){
				console.log('Ошибка при добавлении нового размера');
			});
		}
		else {
			var new_quantity = +$scope.basket[cart_item_id].quantity[index]+1;
				new_size = $scope.basket[cart_item_id].select_size[index];
			$http.get('/index.php?update_quantity=1&type='+$scope.type_basket+'&cart_item_id='+$scope.basket[cart_item_id].cart_item_id+'&new_quantity='+new_quantity+'&new_size='+new_size).success(function (data,status) {
				$scope.basket[cart_item_id].quantity[index] = new_quantity;
				$scope.basket[cart_item_id].common_row_price[index] = new_quantity*$scope.basket[cart_item_id].price;
			}).error(function (data,status){
				console.log('Ошибка при обновлении количества');
			});
		}
		$scope.basket[cart_item_id].new_size = -1;
	}

	$scope.update_quantity = function (cart_item_id, index) {
		console.log ('update_quantity');
		var new_quantity = $scope.basket[cart_item_id].quantity[index]
			new_size = $scope.basket[cart_item_id].select_size[index];
		$http.get('/index.php?update_quantity=1&type='+$scope.type_basket+'&cart_item_id='+$scope.basket[cart_item_id].cart_item_id+'&new_quantity='+new_quantity+'&new_size='+new_size).success(function (data,status) {
				$scope.basket[cart_item_id].quantity[index] = new_quantity;
				$scope.basket[cart_item_id].common_row_price[index] =$scope.basket[cart_item_id].price*new_quantity;
				$scope.basket[cart_item_id].common_row_price[index] = parseFloat($scope.basket[cart_item_id].common_row_price[index]).toFixed(2);
			}).error(function (data,status){
				console.log('Ошибка при обновлении количества');
			});
	}

	$scope.update_size = function (update_size_val, cart_item_id, index_select_size) {
		var index = $scope.basket[cart_item_id].select_size.indexOf(update_size_val);
		if (index == -1) {
				$scope.basket[cart_item_id]['select_size'][index_select_size] = update_size_val;
		} else {
			$scope.basket[cart_item_id].quantity[index]++;
			$scope.basket[cart_item_id].select_size.splice(index_select_size, 1);
			$scope.basket[cart_item_id].quantity.splice(index_select_size, 1);
			$scope.basket[cart_item_id].common_row_price.splice(index_select_size, 1);
		}
		$scope.basket[cart_item_id].common_row_price = parseFloat($scope.basket[cart_item_id].common_row_price).toFixed(2);
	}
	
	$scope.get_common_price_and_count = function () {
		$scope.common_price = 0;
		$scope.common_count = 0;
		for (key in $scope.basket) {
			if (typeof $scope.basket[key].price != 'undefined') {
				var cur_price=+$scope.basket[key].price; 
				for (var i = 0; i < $scope.basket[key].quantity.length; i++){
					$scope.common_price += (cur_price*(+$scope.basket[key].quantity[i]));
					$scope.common_count += +$scope.basket[key].quantity[i];
				}
			}
		}

		$scope.common_price = parseFloat($scope.common_price).toFixed(2);
		if ($scope.type_basket=='') {
			$('span', $('.header-cart')).html($scope.common_count);	
		} else {
			$('span', $('.header-cart-carlsberg')).html($scope.common_count);	
		}
	}
	
	$scope.validation = function () {
		$scope.text.cost_center = $("#cost_center").val();
		var myRe = /^\d{4}_{1}\d{4}$/g;
		$scope.f = {not_valid_cost_center : ''};
		if(myRe.test($scope.text.cost_center)===false||($scope.text.cost_center.length)!=9) {
			$scope.f.not_valid_cost_center = "Некорректный формат.\n Правильный формат записи для поля 'Центр затрат' - \"1234_5678\"";
			return 0;
		}
		return 1;
	}
	$scope.send_order = function () {
		if (!$scope.validation()) {
			return;
		}
		$http({
			    url: 'zakaz_i_dostavka.html',
			    method: "POST",
			    data: { 'common_count':$scope.common_count, 
			    		'common_price':$scope.common_price,
			    		'basket':$scope.basket,
			    		'comment':$scope.text.comment,
			    		'cost_center':$scope.text.cost_center,
			    		'type_of_expenditure':$scope.text.type_of_expenditure}
			}).then(function(response) {
			   	  $(".basket_wrp_table").html('');
			      $(".send_order_show").html('');
			      $(".basket_wrp_table").append("<div id='modal_window' class = 'ang_modal_window'><p>Ваша заявка успешно отправлена.</p><p>Вы будете уведомлены по электронной почте об изменениях состояния заявки.</p></div>");
			      $("html, body").animate({ scrollTop: 0 }, "slow");
			    //$(".basket_wrp_table").append('отправлено');
			    }, 
			    function(response) { // optional
			        console.log ('fail');
			    }
			);
	}
});

appControllers.controller('ModalDemoCtrl', function($scope, $modal, $log) {
   $scope.items = ['item1', 'item2', 'item3'];

  $scope.animationsEnabled = true;

  $scope.open = function (size) {

    var modalInstance = $modal.open({
      animation: $scope.animationsEnabled,
      templateUrl: 'myModalContent.html',
      controller: 'ModalInstanceCtrl',
      size: size,
      resolve: {
        items: function () {
          return $scope.items;
        }
      }
    });

    modalInstance.result.then(function (selectedItem) {
      $scope.selected = selectedItem;
    }, function () {
      $log.info('Modal dismissed at: ' + new Date());
    });
  };

  $scope.toggleAnimation = function () {
    $scope.animationsEnabled = !$scope.animationsEnabled;
  };
});

appControllers.controller('ModalInstanceCtrl', function($scope, $modalInstance, items) {
   $scope.items = items;
  $scope.selected = {
    item: $scope.items[0]
  };

  $scope.ok = function () {
    $modalInstance.close($scope.selected.item);
  };

  $scope.cancel = function () {
    $modalInstance.dismiss('cancel');
  };
});

appControllers.controller('AddRIDFormCtrl', function($scope, $modalInstance, items) {

});

