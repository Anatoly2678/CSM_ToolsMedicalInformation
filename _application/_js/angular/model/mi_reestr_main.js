/**
 * Created by Анатоли on 23.07.2016.
 */

angular.module('PageApp', [])
.controller("SecondCtrl", function($scope) {
    $scope.tracks = [{
      id: "1",
      miName: "Анестезиология и реанимация"
    }, {
      id: "2",
      miName: "Общебольничные и вспомогательные изделия"
    }, {
      id: "3",
      miName: "Реабилитация и физиотерапия"
    }, {
      id: "4",
      miName: "Лаборатория"
    }, {
      id: "5",
      miName: "Пластическая хирургия и косметология"
    }, {
      id: "6",
      miName: "Стоматология"
    }];
  })

// .controller("MiNames", function($scope) {
.controller('MiNames', ['$scope', 'filterFilter', function($scope, filterFilter) {	
    $scope.mins = [{
      id: "1",
      miName: "q1"
    }, {
      id: "1",
      miName: "q2"
    }, {
      id: "3",
      miName: "q3"
    }, {
      id: "1",
      miName: "a4"
    }, {
      id: "2",
      miName: "q5"
    }, {
      id: "2",
      miName: "q6"
    }];
    $scope.Index1 = filterFilter($scope.mins, {id: '1'});
}]);


// var PageApp = angular.module('PageApp', []);
// PageApp.controller('SecondCtrl', function ($scope, $http) {
//     $scope.loading = true;
//     var list = this;
//     list.tracks = [];
//     $http.get('/json/getMIMainReestr')
//         .success(function (data) {
//             list.tracks = data;
//             $scope.loading = false;
//         })
// });