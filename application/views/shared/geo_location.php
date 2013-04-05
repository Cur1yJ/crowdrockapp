<script type="text/javascript">
    function on_geo_success(position) {
        url = '<?php echo site_url("parties/search/"); ?>';
        url = url + '?query=&lat='+position.coords.latitude + '&lon=' + position.coords.longitude;
        window.location = url;
        return false;
        //alert(position.coords.latitude);
        //alert(position.coords.longitude);
        //alert("You are here! (at least within a "+position.coords.accuracy+" meter radius");
    }

    function on_geo_error(error) {
        //alert("geo error occurred.");
        switch(error.code)
        {
            case error.PERMISSION_DENIED:
                alert("You denied the request for Geolocation.");
                break;
            case error.POSITION_UNAVAILABLE:
                alert("Location information is unavailable.");
                break;
            case error.TIMEOUT:
                alert("The request to get user location timed out.");
                break;
            case error.UNKNOWN_ERROR:
                alert("An unknown error occurred.");
                break;
        }
    }

    function searchnearby(){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(on_geo_success, on_geo_error, {maximumAge: 180000, timeout: 10000, enableHighAccuracy: true});
        } else {
            alert('Geolocation is not supported by this browser.');
        }
    }
</script>
