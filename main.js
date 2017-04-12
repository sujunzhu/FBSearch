function tabpick(num){
	$('#myTabs li:eq('.concat(num.toString(),') a')).tab('show');
}

var loca = '';

var options = {
  enableHighAccuracy: true,
  timeout: 5000,
  maximumAge: 0
};

function success(pos) {
  var crd = pos.coords;
  loca = crd.latitude+','+crd.longitude;
};

function error(err) {
	alert('Location service is disabled! Place search is disabled!');
};

function getLocation(){
	navigator.geolocation.getCurrentPosition(success, error, options);
}

var mainApp = angular.module('myApp', [])
.controller('tabCtrl', function($scope,$http,$window){
	$scope.SearchButtonFunc = function(type_search) {
		if(type_search == "fav"){
			alert("Please select type of search");
			return;
		}else if($scope.keyword == undefined || $scope.keyword == null){
			alert("Please enter keywords");
			$scope.showResult=false;
			$scope.showDetail=false;
			$scope.showFavourites=false;
		}else{
			$scope.resultPB=true;
			$scope.showResult=true;
			$scope.showDetail=false;
			$scope.showFavourites=false;
			$http({
				method:'GET',
				url:'main.php',
				params: {
					keyword:$scope.keyword,
					type:type_search,
					center:$window.loca
				}
			}).then(function successCallback(response){
				// this callback will be called asynchronously
				// when the response is available
				var obj = JSON.stringify(response.data);
				var obj = JSON.parse(obj);
				$scope.data = obj.data;
				$scope.previous = obj.paging.previous;
				$scope.next = obj.paging.next;
			}, function errorCallback(response){
				// called asynchronously if an error occurs
				// or server returns response with an error status
			});
		}
	};
	$scope.UpdateButtonFunc = function(urlbn) {
		$scope.resultPB=true;
		$http({
			method:'GET',
			url:urlbn
		}).then(function successCallback(response){
			// this callback will be called asynchronously
			// when the response is available
			var obj = JSON.stringify(response.data);
			var obj = JSON.parse(obj);
			$scope.data = obj.data;
			$scope.previous = obj.paging.previous;
			$scope.next = obj.paging.next;
		}, function errorCallback(response){
			// called asynchronously if an error occurs
			// or server returns response with an error status
		});
	};
	$scope.DetailButtonFunc = function(itemID,type_ss){		
		$scope.albumPB = true;
		$scope.postPB = true;
		$scope.showResult=false;
		$scope.showDetail=true;
		$scope.showFavourites=false;
		$http({
			method:'GET',
			url:'main.php',
			params: {
				type:type_ss,
				id:itemID
			}
		}).then(function successCallback(response){
			// this callback will be called asynchronously
			// when the response is available
			var obj = JSON.stringify(response.data);
			var obj = JSON.parse(obj);
			$scope.detail = obj;
			$scope.albums = obj.albums;
			$scope.posts = obj.posts;
			if(obj.albums == undefined || $scope.albums.length == 0){
				$scope.albumPB = false;
			}
			if(obj.posts == undefined || $scope.posts.length == 0){
				$scope.postPB = false;
			}
		}, function errorCallback(response){
			// called asynchronously if an error occurs
			// or server returns response with an error status
		});
	};
	$scope.InitializeFunc = function(){
		if(localStorage.getItem("favourites")==undefined){
			var favourites = new Array();
			$scope.favs = favourites;
			localStorage.setItem("favourites", JSON.stringify(favourites));
		}else{
			var favourites = JSON.parse(localStorage.getItem("favourites"));
			$scope.favs = favourites;
			localStorage.setItem("favourites", JSON.stringify(favourites));
		}
	}
	$scope.FavouriteButtonFunc = function(itemID,photourl,name_,type_){
		if(localStorage.getItem("favourites")==undefined){
			var favourites = new Array();
			var obj = {"id":itemID,"url":photourl,"name":name_,"type":type_};
			favourites.push(obj);
			$scope.favs = favourites;
			localStorage.setItem("favourites", JSON.stringify(favourites));
		}else{
			var favourites = JSON.parse(localStorage.getItem("favourites"));
			var obj = {"id":itemID,"url":photourl,"name":name_,"type":type_};
			favourites.push(obj);
			$scope.favs = favourites;
			localStorage.setItem("favourites", JSON.stringify(favourites));
		}
	}
	$scope.UnfavouriteButtonFunc = function(itemID){
		var favourites = JSON.parse(localStorage.getItem("favourites"));
		var i = favourites.length;
		while (i--) {
		   if (favourites[i].id == itemID) {
			   favourites.splice(i,1);
		   }
		}
		$scope.favs = favourites;
		localStorage.setItem("favourites", JSON.stringify(favourites));
	}
	
	$scope.Post_fb = function(photourl,name_){
		FB.ui({
		 app_id: '1845517239059399',
		 method: 'feed',
		 link: "http://csci571hw8-163711.appspot.com/",
		 picture: photourl,
		 name: name_,
		 caption: "FB SEARCH FROM USC CSCI571",
		 }, function(response){
		 if (response && !response.error_message){
			 alert("Posted Successfully");
		 }
		 else{
			 alert("Not Posted");
		 }
		});
	}

	$scope.contains = function(itemID) {
		var favourites = JSON.parse(localStorage.getItem("favourites"));
		var i = favourites.length;
		while (i--) {
		   if (favourites[i].id == itemID) {
			   return true;
		   }
		}
		return false;
	}
	$scope.clearFa = function(){
		var favourites = new Array();
		$scope.favs = favourites;
		localStorage.setItem("favourites", JSON.stringify(favourites));
	}
	$scope.BackButtonFunc = function(){
		if($scope.numTab==5){
			$scope.showResult=false;
			$scope.showDetail=false;
			$scope.showFavourites=true;
		}else{
			$scope.showResult=true;
			$scope.showDetail=false;
			$scope.showFavourites=false;
		}
	}
	$scope.ClearButtonFunc = function() {
		$scope.keyword = null;
		$scope.type_s = 'user';
		tabpick(0);
		$scope.showResult=false;
		$scope.showDetail=false;
		$scope.showFavourites=false;
	}
	$scope.$on('repeatFinished',function(){
		$scope.resultPB = false;
	});
	$scope.$on('repeatFinishedAlbum',function(){
		$scope.albumPB = false;
	});
	$scope.$on('repeatFinishedPost',function(){
		$scope.postPB = false;
	});
});

mainApp.directive('repeatDone',function($timeout){
	return function(scope, element, attrs){
		if(scope.$last){
			$timeout(function(){
				scope.$emit('repeatFinished');
			});
		}
	}
});

mainApp.directive('repeatDoneAlbum',function($timeout){
	return function(scope, element, attrs){
		if(scope.$last){
			$timeout(function(){
				scope.$emit('repeatFinishedAlbum');
			});
		}
	}
});

mainApp.directive('repeatDonePost',function($timeout){
	return function(scope, element, attrs){
		if(scope.$last){
			$timeout(function(){
				scope.$emit('repeatFinishedPost');
			});
		}
	}
});