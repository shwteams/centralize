<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="../services/js/jquery-2.2.3.min.js"></script>
    <script src="angularJs.js"></script>
    <script src="node_modules/angular-datatables/bundles/angular-datatables.umd.min.js"></script>
    <script src="../services/js/datatable.js"></script>
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <title>Test angular js</title>
</head>
<body>
    <div ng-app="customerApp" ng-controller="customerController" class="container">
        <br>
        <h3 align="center">
            How to use Angular Js datatables and PHP
        </h3>
        <table datatable="ng" dt-options="vm.dtOption" class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Nom</th>
                    <th>Prenom</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="customer in customers">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ customer.str_FILE }}</td>
                    <td>{{ customer.str_PARAM }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <script>
        var app = angular.module('customerApp', ['datatables']);
        app.controller('customerController', function ($scopt, $http) {
            $http.get('fetch.php').success(function (data, status, headers, config) {
                $scopt.customers = data;
            })
        })
    </script>
</body>
</html>