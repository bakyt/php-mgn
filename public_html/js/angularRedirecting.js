angular.module( "ijara", [ "ngRoute" ] );
angular.module( "ijara" ).directive(
    "AppController",
    function AppController( $scope, $location, $route ) {
        alert($location.url());
        document.preventDefault();
        // When the location changes, capture the state of the full URL.
        $scope.$on(
            "$locationChangeSuccess",
            function locationHref() {


            }
        );

    }
);