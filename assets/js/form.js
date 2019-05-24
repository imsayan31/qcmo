jQuery(document).ready(function ($) {
    
    $( "#myform" ).validate({
    rules: {
        phone_type: {
          required: true
        },
        how_many_people:{
            required:true
        },
        children: {
          required: true
        },
        description:{
            required:true
        },
        years: {
             required:true
        },
        room_rent: {
           required:true
        },
        residence:{
             required:true
        },
        fenced_yard:{
             required:true
        },
        fence:{
             required:true
        },
        high:{
             required:true
        },        
        away_from_home:{
             required:true
        },
        dog_alone_time:{
             required:true
        },
        add_pet:{
             required:true
        },
        breed:{
             required:true
        },
        pet_name:{
             required:true
        },
        pet_age:{
             required:true
        },
        gender:{
             required:true
        },
        up_to_date:{
             required:true
        },
        veterinarian:{
             required:true
        },
        veterinarian_name:{
             required:true
        },
        veterinarian_phone:{
             required:true
        },
        pet_shelter:{
             required:true
        },
        hear_about_rescue:{
             required:true
        },
        comment:{
             required:true
        },
        agree:{
             required:true
        },
        full_name:{
             required:true
        },       
    }
    
    });
    $("input[name='fenced_yard']:checked").click(function() {
        var test = $(this).val();
        console.log(1);
        $("div.fence").hide();
        $("#fenced_yard_".test).show();
    });

});

    
    




