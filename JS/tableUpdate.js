$(document).ready(function () {
    $('a.Car1').on('click', function () {
        // var elem = document.querySelector('a.Car1');

        $.ajax({
            method: "POST",
            url: "forms/tableForm.php",
            data: {value: 0},
            success: function (res) {
                console.log(res);
                $('#Result').html(res);
            }
        });
    })

    $('a.Car2').on('click', function () {

        $.ajax({
            method: "POST",
            url: "forms/tableForm.php",
            data: {value: 1},
            success: function (res) {
                console.log(res);
                $('#Result').html(res);
            }
        });
    });
    $('a.Car3').on('click', function () {
        $.ajax({
            method: "POST",
            url: "forms/tableForm.php",
            data: {value: 2},
            success: function (res) {
                console.log(res);
                $('#Result').html(res);
            }
        });
    });
    $('a.Car4').on('click', function () {
        $.ajax({
            method: "POST",
            url: "forms/tableForm.php",
            data: {value: 3},
            success: function (res) {
                console.log(res);
                $('#Result').html(res);
            }
        });
    });
});


