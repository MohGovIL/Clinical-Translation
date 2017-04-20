$( document ).ready(function() {

    $(".constants").keypress(function(event){
        var string = /[^א-ת]/g;
        var key = String.fromCharCode(event.which);
        if (event.keyCode == 8 || event.keyCode == 37 || event.keyCode == 39 || string.test(key)) {
            return true;
        }

        return false;
    });



    $("#langaddform").validate();



    var clients = [
        { "Constants": "A Clay", "Lang": 4, },
        { "Constants": "A Clay", "Lang": 4, },
        { "Constants": "A Clay", "Lang": 4, },
        { "Constants": "A Clay", "Lang": 4, },
        { "Constants": "A Clay", "Lang": 4, },
        { "Constants": "A Clay", "Lang": 4, },
        { "Constants": "A Clay", "Lang": 4, },
        { "Constants": "A Clay", "Lang": 4, },
        { "Constants": "A Clay", "Lang": 4, },
        { "Constants": "A Clay", "Lang": 4, },
        { "Constants": "A Clay", "Lang": 4, },
        { "Constants": "A Clay", "Lang": 4, },
        { "Constants": "A Clay", "Lang": 4, },
        { "Constants": "A Clay", "Lang": 4, },


    ];


    $("#jsGrid").jsGrid({
        width: "100%",
        height: "700px",

        filtering: true,
        inserting: false,
        editing: true,
        sorting: true,
        paging: true,
        autoload: true,
        pageSize: 20,
        pageButtonCount: 5,
        deleteConfirm: "Do you really want to delete client?",


        controller: {
            loadData: function(filter) {
                return $.ajax({
                    type: "POST",
                    url: "pages/edit.php?p=filter",
                    data: filter
                });
            },


            updateItem: function(item) {
                return $.ajax({
                    type: "POST",
                    url: "pages/edit.php?p=update",
                    data: item
                });
            },

            deleteItem: function(item) {
                return $.ajax({
                    type: "POST",
                    url: "/items",
                    data: item
                });
            },
        },

        //data: clients,
        fields: [
            { name: "Constant_id", type: "text", width: 150, editing: false, visible: false},
            { name: "Lang_id", type: "text", width: 150, editing: false, visible: false},
            { name: "Constants", type: "text", width: 150},
            { name: "Lang", type: "text", width: 150 },
            { type: "control" }
        ]
    });
});