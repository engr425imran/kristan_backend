var map;
var overviewMap;
var LAT = "28.0646888";
var LNG = "-80.6252107";
var myLatlng = new google.maps.LatLng(LAT,LNG);
var geocoder = new google.maps.Geocoder();
var infowindow = new google.maps.InfoWindow();
var marker = new google.maps.Marker({map: map,anchorPoint: new google.maps.Point(0, -29)});
var style = [
  {
    "stylers": [
      {
        "saturation": -55
      }
    ]
  },
  {
    "elementType": "geometry.fill",
    "stylers": [
      {
        "saturation": 45
      },
      {
        "lightness": 15
      },
      {
        "weight": 1.5
      }
    ]
  }
];

function onlynumbers(that){
    if (/\D/g.test(that.value)){
    that.value = that.value.replace(/\D/g, '');
  }
}


// $('#onlunumberInput').keyup(function(e){
//   if (/\D/g.test(that.value))
//   {
//     // Filter non-digits from input value.
//     that.value = that.value.replace(/\D/g, '');
//   }
// });

function loadDefaultMenuImage(imgTemp) {
    imgTemp.src = base_url+"assets/img/no.png";
}

function loadDefaultUserImage(imgTemp) {
    imgTemp.src = base_url+"assets/img/1.jpg";
}

function loadDefaultUserEditImage(imgTemp) {
    imgTemp.src = base_url+"assets/img/no.jpg";
}

function previewImage(input,previewid) {
    if(input.files && input.files[0]){
        var imgPath = input.files[0].name;
        var extn = imgPath.substring(imgPath.lastIndexOf('.') + 1).toLowerCase();
        if(extn == "gif" || extn == "png" || extn == "jpg" || extn == "jpeg") {
            if(typeof (FileReader) != "undefined"){
                //$("#preloader").show();
                var reader = new FileReader();
                reader.readAsDataURL(input.files[0]);
                console.log(reader);
                reader.onload = function (e) {
                    $('#'+previewid).attr('src',e.target.result);
                    /*var fd = new FormData();
                    fd.append('file', input.files[0]);
                    $.ajax({
                        url:base_url+'home/uploadImage/'+id+'/'+type,
                        data:fd,
                        processData:false,
                        contentType:false,
                        type:'POST',
                        dataType:'json',
                        success:function(data){
                        }
                    })*/
                };       
            }else{
                console.log("This browser does not support FileReader.");
            }
        }else{
            console.log("Please select only images");     
        }
    }
}

function googleAutoComplete() {
    var input = (document.getElementById('pac-inputs'));
    var autocomplete = new google.maps.places.Autocomplete(input);
    google.maps.event.addListener(autocomplete, 'place_changed', function() {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            console.log("Autocomplete's returned place contains no geometry");
            return;
        }
        console.log(place);
        var pos = {
            lat: place.geometry.location.lat(),
            lng: place.geometry.location.lng()
        };
        document.getElementById("latitude").value = place.geometry.location.lat();
        document.getElementById("longitude").value = place.geometry.location.lng();
        if (place) {
            var state2 = '';
            for (var i=0; i<place.address_components.length; i++){
                for (var b=0;b<place.address_components[i].types.length;b++){
                    if (place.address_components[i].types[b] == "locality"){
                        state2= place.address_components[i];
                        state2 = state2.long_name;
                        console.log("state2",state2);
                        //document.getElementById("pac-inputs").value = state2;
                        $('#pac-inputs').val(place.formatted_address);
                    }
                    if(state2==''){
                        if (place.address_components[i].types[b] == "administrative_area_level_2"){
                            state2= place.address_components[i];
                            state2 = state2.long_name;
                            console.log("state2",state2);
                            //document.getElementById("pac-inputs").value = state2;
                            $('#pac-inputs').val(place.formatted_address);
                        }
                    }
                    if(state2==''){
                        if (place.address_components[i].types[b] == "administrative_area_level_1"){
                            state2= place.address_components[i];
                            state2 = state2.long_name;
                            console.log("state2",state2);
                            //document.getElementById("pac-inputs").value = state2;
                            $('#pac-inputs').val(place.formatted_address);
                        }
                    }
                }
            }
        }
        document.getElementById("latitude").value = pos.lat;
        document.getElementById("longitude").value = pos.lng;
    });
}

function addGateInitializeMap(lat,long){
    LAT = (lat!='')?lat:LAT;
    LNG = (long!='')?long:LNG;
    /*console.log(LAT);
    console.log(LNG);*/
    var styledMap = new google.maps.StyledMapType(style,{name: "Styled Map"});
    myLatlng = new google.maps.LatLng(LAT,LNG);

    var overviewMapOptions = {
        zoom: 12,
        mapTypeId: google.maps.MapTypeId.SATELLITE,
        center: myLatlng,
        disableDefaultUI: true
    }

    var mapOptions = {
        zoom: 12,
        panControl: false,
        mapTypeControl: false,
        scaleControl: false,
        fullscreenControl: false,
        streetViewControl: false,
        zoomControl : true,
        overviewMapControl: false,
        center: myLatlng,
        disableDefaultUI: true
    };
    map = new google.maps.Map(document.getElementById("myMap"), mapOptions);
    overviewMap = new google.maps.Map(document.getElementById('overview-map'), overviewMapOptions);

    google.maps.event.addListener(map, 'bounds_changed', (function () {
        overviewMap.setCenter(map.getCenter());
        overviewMap.setZoom(map.getZoom());
    }));

    marker.setVisible(false);
    marker = new google.maps.Marker({
        map: map,
        position: myLatlng,
        draggable: true 
    });
    marker.setVisible(true);
    map.mapTypes.set('map_style', styledMap);
    map.setMapTypeId('map_style');

    var input = document.getElementById('pac-inputs');        
    var autocomplete = new google.maps.places.Autocomplete(input);
    autocomplete.addListener('place_changed', function(){
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            console.log("Autocomplete's returned place contains no geometry");
            return;
        }
        document.getElementById('latitude').value = place.geometry.location.lat();
        document.getElementById('longitude').value = place.geometry.location.lng();

        var latlng = {
            lat: place.geometry.location.lat(),
            lng: place.geometry.location.lng()
        };
        marker.setVisible(false);
        marker = new google.maps.Marker({
            position : latlng,
            map : map,
            draggable: true 
        });
        map.setCenter(latlng);
        
        geocoder.geocode({'latLng': latlng}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    //$('#address').val(results[0].formatted_address);
                    $('#latitude').val(marker.getPosition().lat());
                    $('#longitude').val(marker.getPosition().lng());
                    //updateAddress();
                }
            }
        });
    });

    google.maps.event.addListener(map,'click',function(event) {
        var lat = event.latLng.lat(); 
        var lng = event.latLng.lng();
        document.getElementById("latitude").value = lat;
        document.getElementById("longitude").value = lng;
        marker.setVisible(false);
        var latlng = new google.maps.LatLng(lat, lng);
        // now, create the marker
        marker = new google.maps.Marker({
            position : latlng,
            map : map,
            draggable: true 
        });
        marker.setVisible(true);
        //getAddress(latlng);
        geocoder.geocode({'latLng': latlng}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('#pac-inputs').val(results[0].formatted_address);
                    $('#latitude').val(marker.getPosition().lat());
                    $('#longitude').val(marker.getPosition().lng());
                    //updateAddress();
                }
            }
        });

        google.maps.event.addListener(marker, 'dragend', function() {
            geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[0]) {
                        $('#pac-inputs').val(results[0].formatted_address);
                        $('#latitude').val(marker.getPosition().lat());
                        $('#longitude').val(marker.getPosition().lng());
                        //updateAddress();
                    }
                }
            });
        });
    });

    google.maps.event.addListener(marker, 'dragend', function() {
        geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[0]) {
                    $('#pac-inputs').val(results[0].formatted_address);
                    $('#latitude').val(marker.getPosition().lat());
                    $('#longitude').val(marker.getPosition().lng());
                    //updateAddress();
                }
            }
        });
    });
}

var isSatellite = false;
function changeMap(){
    if(isSatellite){
        var mapType = new google.maps.StyledMapType(style, {name: "Styled Map"});
        map.mapTypes.set('map_style', mapType);
        map.setMapTypeId('map_style');
        overviewMap.setMapTypeId('satellite');
        isSatellite = false;
    }else{
        var mapType = new google.maps.StyledMapType(style, {name: "Styled Map"});
        overviewMap.mapTypes.set('map_style', mapType);
        overviewMap.setMapTypeId('map_style');
        map.setMapTypeId('satellite');
        isSatellite = true;
    }
}

/*function getAddress(latLng) {
    geocoder = new google.maps.Geocoder();
   // console.log(geocoder);
    geocoder.geocode( {'latLng': latLng},function(results, status) {
       // console.log(document.getElementById("address_event"));
        if(status == google.maps.GeocoderStatus.OK) {
            if(results[0]) {
              console.log(results[0]);
              var state2 = '';
                if(document.getElementById("address"))
                  document.getElementById("address").value = results[0].formatted_address;
                for (var i=0; i<results[0].address_components.length; i++){
                  for (var b=0;b<results[0].address_components[i].types.length;b++){
                      if (results[0].address_components[i].types[b] == "locality"){
                          state2= results[0].address_components[i];
                          state2 = state2.long_name;
                          document.getElementById("city").value = state2;
                      }
                      if(state2==''){
                          if (results[0].address_components[i].types[b] == "administrative_area_level_2"){
                              state2= results[0].address_components[i];
                              state2 = state2.long_name;
                              document.getElementById("city").value = state2;
                          }
                      }
                      if (results[0].address_components[i].types[b] == "administrative_area_level_1"){
                          state2 = results[0].address_components[i];
                          state2 = state2.long_name;
                          document.getElementById("state").value = state2;
                      }
                      if (results[0].address_components[i].types[b] == "country"){
                          state2 = results[0].address_components[i];
                          state2 = state2.long_name;
                          document.getElementById("country").value = state2;
                      }
                  }
              }
            }else {
                if(document.getElementById("address"))
                document.getElementById("address").value = "No results";
            }
        }else{
            if(document.getElementById("address"))
            document.getElementById("address").value = status;
        }
    });
}*/
function getFormData($form){
    var unindexed_array = $form.serializeArray();
    var indexed_array = {};
    console.log(unindexed_array);
    $.map(unindexed_array, function(n, i){
        indexed_array[n['name']] = n['value'];
    });
    return indexed_array;
}

function generateReport(reportType){
    var data = {};
    data.start_date = $('#start_date').val();
    data.end_date = $('#end_date').val();
    data.reportType = reportType;
    if(data.start_date==''){
        toastr.warning('Please select from date.');
    }else if(data.end_date==''){
        toastr.warning('Please select to date.');
    }else if(data.end_date < data.start_date){
        toastr.warning('Please select valid to date.');
    }else{
        $.ajax({        
            type : 'POST',
            url : 'admin/getReport',
            headers : {},
            contentType : 'application/json',
            dataType: 'json',
            data: JSON.stringify(data),
            success : function(response) {
              console.log(response);
                if(response.success){
                    console.log("true");
                    var html = '';
                    if(response.userReport){
                        $("#reportData").show();
                        $("#revenueReportData").hide();
                        $("#ratingReportData").hide();
                        $("#searchData").hide();
                        for (var i = 0; i < response.userReport.length; i++) {
                            var result = response.userReport[i];
                            var serialNo = i+1;
                            var user_type = "";
                            if(result.user_type == 0){
                                user_type = "Customer";
                            }else if(result.user_type == 1){
                                user_type = "Manager";
                            }else if(result.user_type == 2){
                                user_type = "Valet";
                            }
                            html = html + '<tr><td>'+serialNo+'</td><td>'+user_type+'</td><td>'+result.first_name+' '+result.last_name+'</td><td>'+result.email+'</td><td>'+result.phone_number+'</td></tr>';
                        }
                        $("#datatableBody").html(html);
                    }
                    if(response.revenuReport){
                        $("#revenueReportData").show();
                        $("#searchData").hide();
                        for (var i = 0; i < response.revenuReport.length; i++) {
                            var result = response.revenuReport[i];
                            if($(result.vehicle_request).length > 0){
                                var serialNo = i+1;
                                tip_amount = parseFloat(result.tip).toFixed(2);
                                html = html + '<tr><td>'+serialNo+'</td><td>'+result.vehicle_request.Valetname+'</td><td>'+result.vehicle_request.location+'</td><td>$'+tip_amount+'</td></tr>';
                            }
                        }
                        $("#datatableRevenueBody").html(html);
                    }
                    if(response.ratingReport){
                        $("#reportData").hide();
                        $("#revenueReportData").hide();
                        $("#ratingReportData").show();
                        $("#searchData").hide();
                        for (var i = 0; i < response.ratingReport.length; i++) {
                            var result = response.ratingReport[i];
                            var serialNo = i+1;
                            html = html + '<tr><td>'+serialNo+'</td><td>'+result.clientname+'</td><td>'+result.vehicle_request.Valetname+'</td><td>'+result.message+'</td><td>'+result.rating+'</td></tr>';
                        }
                        $("#datatableRatingBody").html(html);
                    }
                }else{
                    console.log("false");
                    $("#reportData").hide();
                    $("#searchData").show();
                    toastr.error('No data found at this date range.');
                }
            },
        });
    }
}