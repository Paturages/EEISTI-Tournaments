// Loads sidebar handling
$(".button-collapse").sideNav();

// Team add/remove buttons
var removePlayer = function() {
    $(this).parent().addClass("team-hidden").fadeOut("fast");
    $(".player-add").removeAttr("disabled");
};
var addPlayer = function() {
    $('.team-hidden:first').removeClass("team-hidden").fadeIn().children(".player-remove").click(removePlayer);
    if ($(".team-hidden").length == 0)
        $(".player-add").attr("disabled", "disabled");
};
$(".player-add").click(addPlayer);

// Form initializing
var initializeForm = function() {
    if (current_game.num_players > 1) {
        // Prepare team form
        for (var i = 0 ; i < current_game.num_players ; i++) {
            p = $("#team-player-template")
                .children()
                .clone()
                .addClass("team-hidden").hide()
                .appendTo("#team-players")
                .children("label").html("Joueur "+(i+1))
            ;
            $("#team-players .team-player:last-child label[for='player-real-name-1']").attr("for", "player-real-name-"+(i+1));
            $("#team-players .team-player:last-child label[for='player-name-1']").attr("for", "player-name-"+(i+1));
            $("#team-players .team-player:last-child #player-real-name-1").attr("id", "player-real-name-"+(i+1));
            $("#team-players .team-player:last-child #player-name-1").attr("id", "player-name-"+(i+1));
        }
        $('.team-hidden:first-of-type').children("button").remove();
        $('.player-nickname-field').html(current_game.nickname_field);
        if ($.isEmptyObject(edit_user)) {
            $('#team-email-field').show();
            $('#team-password-field').hide();
            $('.team-hidden:first-of-type').removeClass("team-hidden").show();
            $('#team-form p').html("Un code sera fourni par e-mail pour confirmation de l'inscription.");
        } else {
            $('#team-email-field').hide();
            $('#team-password-field').show();
            $('#team-form p').html("Entrer le code donné à l'inscription par e-mail. ")
                .append($('<a href="#!">Oublié ?</a>')
                    .click(function() {
                        $('#team-form').closeModal();
                        triggerWhat = forgotSubmit;
                        $('#forgot-form').openModal();
                    })
            );
            // Edit mode: prefill form
            $('#team-name').val(edit_user.name).prev().addClass("active");
            if (edit_user.campus == "Cergy")
                $('#team-cergy').prop("checked", true);
            else if (edit_user.campus == "Pau")
                $('#team-pau').prop("checked", true);
            else
                $('#team-mixte').prop("checked", true);
            $.each(edit_user.players, function(i, player) {
                $('.team-hidden:first .player-real-name').val(player.real_name).prev().addClass("active");
                $('.team-hidden:first .player-name').val(player.name).prev().addClass("active");
                if (player.campus == "Pau")
                    $('.team-hidden:first .player-campus').prop("checked", true);
                addPlayer();
            });
        }
        if (current_game.multicampus == 0)
            $('#team-campus, .switch').hide();
        else
            $('#team-campus, .switch').show();

        triggerWhat = teamSubmit;
        $('#team-form').openModal({
            // On modal close...
            complete: function() {
                triggerWhat = null;
                edit_user = {};
                $("#team-errors").empty();
                $(":input[type=text]").val('');
                $(".player-campus").prop("checked", false);
                $("#team-players").empty();
                $("player-add").removeAttr("disabled");
            }
        });
        $('#team-form').scrollTop(0);
    } else {
        // Prepare solo form
        $('#solo-nickname-field').html(current_game.nickname_field);
        if ($.isEmptyObject(edit_user)) {
            $('#solo-form p').html("Un code sera fourni par e-mail pour confirmation de l'inscription.");
            $('#solo-email-field').show();
            $('#solo-password-field').hide();
        } else {
            $('#solo-email-field').hide();
            $('#solo-password-field').show();
            $('#solo-form p').html("Entrer le code donné à l'inscription par e-mail. ")
                .append($('<a href="#!">Oublié ?</a>')
                    .click(function() {
                        $('#solo-form').closeModal();
                        triggerWhat = forgotSubmit;
                        $('#forgot-form').openModal();
                    })
            );
            // Edit mode: Prefill form
            $('#solo-name').val(edit_user.name).prev().addClass("active");
            $('#solo-real-name').val(edit_user.real_name).prev().addClass("active");
            if (edit_user.campus == "Cergy")
                $('#solo-cergy').prop("checked", true);
            else
                $('#solo-pau').prop("checked", true);
        }
        if (current_game.multicampus == 0)
            $('#solo-campus').hide();
        else
            $('#solo-campus').show();

        triggerWhat = soloSubmit;
        $('#solo-form').openModal({
            complete: function() {
            // On modal close...
                $('#solo-form label').removeClass("active");
                triggerWhat = null;
                edit_user = {};
                $("#solo-errors").empty();
                $(":input[type=text]").val('');
            }
        });
        $('#solo-form').scrollTop(0);
    }
};

// Keypress initializing, for ENTER key presses in forms
var triggerWhat = null;
$(document).keypress(function(e) {
    if (e.which == 13) {
        triggerWhat();
    }
});

// Form handling
function soloSubmit() {
    $("#solo-form .modal-content").append('<div class="progress"><div class="indeterminate"></div></div>');
    $("#solo-errors").empty();
    if ($.isEmptyObject(edit_user))
        $.post('api/entries/create/'+current_game.rowid, {
            real_name: $("#solo-real-name").val(),
            name: $("#solo-name").val(),
            email: $("#solo-email").val(),
            campus: current_game.multicampus > 0 ? $("#solo-campus input:checked").val() : "Cergy"
        })
        .done(function() {
            $(".progress").remove();
            triggerWhat = null;
            $("#solo-form").closeModal();
            // For some reason, the 'complete' modal callback isn't called...
            $('#solo-form label').removeClass("active");
            edit_user = {};
            $("#solo-errors").empty();
            $(":input[type=text]").val('');
            Materialize.toast("Un e-mail de confirmation a été envoyé.", 5000);
        })
        .fail(function(data) {
            $(".progress").remove();
            $.each(data.responseJSON, function(k, message) {
                $("#solo-errors").append("<p>"+message+"</p>");
            });
            $("#solo-form").animate({scrollTop: 0}, 2000);
        })
        ;
    else
        $.post('api/entries/edit/'+edit_user.rowid, {
            real_name: $("#solo-real-name").val(),
            name: $("#solo-name").val(),
            password: $("#solo-password").val(),
            campus: current_game.multicampus > 0 ? $("#solo-campus input:checked").val() : "Cergy"
        })
        .done(function() {
            $(".progress").remove();
            triggerWhat = null;
            $("#solo-form").closeModal();
            // For some reason, the 'complete' modal callback isn't called...
            $('#solo-form label').removeClass("active");
            edit_user = {};
            $("#solo-errors").empty();
            $(":input[type=text]").val('');
            Materialize.toast("Entrée modifiée.", 3000);
            $('#entries').empty();
            getSoloEntries(current_game);
        })
        .fail(function(data) {
            $(".progress").remove();
            $.each(data.responseJSON, function(k, message) {
                $("#solo-errors").append("<p>"+message+"</p>");
            });
            $("#solo-form").animate({scrollTop: 0}, 2000);
        })
        ;
}
$("#solo-submit").click(soloSubmit);

function teamSubmit() {
    $("#team-form .modal-content").append('<div class="progress"><div class="indeterminate"></div></div>');
    $("#team-errors").empty();
    var real_names = [];
    var p_names = [];
    var p_campuses = [];
    $("#team-players .team-player:not(.team-hidden)").each(function() {
        real_names.push($(this).find(".player-real-name").val());
        p_names.push($(this).find(".player-name").val());
        p_campuses.push($(this).find(".player-campus:checked").length > 0 ? "Pau" : "Cergy");
    });
    if ($.isEmptyObject(edit_user))
        $.post('api/entries/create/'+current_game.rowid, {
            name: $("#team-name").val(),
            email: $("#team-email").val(),
            campus: current_game.multicampus > 0 ? $("#team-campus input:checked").val() : "Cergy", 
            real_name: real_names,
            p_name: p_names,
            p_campus: p_campuses
        })
        .done(function() {
            $(".progress").remove();
            triggerWhat = null;
            $("#team-form").closeModal();
            // For some reason, the 'complete' modal callback isn't called...
            edit_user = {};
            $("#team-errors").empty();
            $(":input[type=text]").val('');
            $(".player-campus").prop("checked", false);
            $("#team-players").empty();
            $("player-add").removeAttr("disabled");
            Materialize.toast("Un e-mail de confirmation a été envoyé.", 5000);
            $('#entries').empty();
            getTeamEntries(current_game);
        })
        .fail(function(data) {
            $(".progress").remove();
            $.each(data.responseJSON, function(k, message) {
                $("#team-errors").append("<p>"+message+"</p>");
            });
            $("#team-form").animate({scrollTop: 0}, 2000);
        })
        ;
    else
        $.post('api/entries/edit/'+edit_user.rowid, {
            name: $("#team-name").val(),
            password: $("#team-password").val(),
            campus: current_game.multicampus > 0 ? $("#team-campus input:checked").val() : "Cergy", 
            real_name: real_names,
            p_name: p_names,
            p_campus: p_campuses
        })
        .done(function() {
            $(".progress").remove();
            triggerWhat = null;
            $("#team-form").closeModal();
            // For some reason, the 'complete' modal callback isn't called...
            edit_user = {};
            $("#team-errors").empty();
            $(":input[type=text]").val('');
            $(".player-campus").prop("checked", false);
            $("#team-players").empty();
            $("player-add").removeAttr("disabled");
            Materialize.toast("Entrée modifiée.", 3000);
            $('#entries').empty();
            getTeamEntries(current_game);
        })
        .fail(function(data) {
            $(".progress").remove();
            $.each(data.responseJSON, function(k, message) {
                $("#team-errors").append("<p>"+message+"</p>");
            });
            $("#team-form").animate({scrollTop: 0}, 2000);
        })
        ;
}
$("#team-submit").click(teamSubmit);

function verifySubmit() {
    $("#verify-form .modal-content").append('<div class="progress"><div class="indeterminate"></div></div>');
    $("#verify-errors").empty();
    $.post('api/entries/delete/'+edit_user.rowid, {
        password: $("#verify-password").val()
    })
    .done(function() {
        $(".progress").remove();
        triggerWhat = null;
        $("#verify-form").closeModal();
        // For some reason, the 'complete' modal callback isn't called...
        edit_user = {};
        $("#verify-password").val('');
        $("#verify-errors").empty();
        Materialize.toast("Entrée supprimée.", 3000);
        $('#entries').empty();
        if (current_game.num_players > 1)
            getTeamEntries(current_game);
        else
            getSoloEntries(current_game);
    })
    .fail(function(data) {
        $(".progress").remove();
        $("#verify-errors").append("<p>"+data.responseJSON[0]+"</p>");
        $("#verify-errors").animate({scrollTop: 0}, 2000);
    })
    ;
}
$('#verify-submit').click();

function forgotSubmit() {
    $("#forgot-form .modal-content").append('<div class="progress"><div class="indeterminate"></div></div>');
    $("#forgot-errors").empty();
    $.post('api/entries/forgot/'+edit_user.rowid, {
        email: $("#forgot-email").val()
    })
    .done(function() {
        $(".progress").remove();
        triggerWhat = null;
        $("#forgot-form").closeModal();
        // For some reason, the 'complete' modal callback isn't called...
        edit_user = {};
        $("#forgot-email").val('');
        $("#forgot-errors").empty();
        Materialize.toast("Un nouveau mot de passe a été envoyé par e-mail.", 3000);
        $('#entries').empty();
        if (current_game.num_players > 1)
            getTeamEntries(current_game);
        else
            getSoloEntries(current_game);
    })
    .fail(function(data) {
        $(".progress").remove();
        $("#forgot-errors").append("<p>"+data.responseJSON[0]+"</p>");
        $("#forgot-errors").animate({scrollTop: 0}, 2000);
    })
    ;
}
$("#forgot-submit").click(forgotSubmit);

var dateFormat = function(time) {
    return time.toLocaleDateString() + ' ' + time.getHours() + ':' + (time.getMinutes() < 10 ? '0' : '') + time.getMinutes();
};

var current_game = {}; // Stores the current game
var edit_user = {}; // Stores the current entry being edited

// Game-specific form settings
var getTeamEntries = function(game) {
    $.get("api/entries/"+game.rowid, function(entries) {
        if (entries.length == 0) {
            return $('#entries').append('<p class="flow-text center">Aucune inscription pour le moment.</p>');
        }

        var items = $('<ul class="collapsible" data-collapsible="expandable"></ul>');
        $.each(entries, function(i, team) {
            var item = $('<li class="collection-item avatar"></li>');
            // Append header
            item.append('<div class="collapsible-header"><i class="mdi-social-group"></i>'+team.name+(game.multicampus > 0 ? ' ('+team.campus+')' : '')+'<span class="right">'+dateFormat(new Date(+team.time*1000))+' &nbsp; <label class="right hide-on-small-only">Cliquer pour dérouler</label></span></div>');

            var table = $('<table><thead><tr><th>Nom</th><th>'+game.nickname_field+'</th>'+(game.multicampus > 0 ? '<th>Campus</th>' : '')+'<th>Date d\'inscription</th></tr></thead></table>');
            var l_edit, l_delete, players = $('<tbody></tbody>');
            $.each(team.players, function(j, player) {
                l_edit = $('<td><a href="#"><i class="mdi-content-create circle blue"></i></a></td>').click(function() {
                    edit_user = team;
                    initializeForm();
                });
                l_delete = $('<td><a href="#"><i class="mdi-content-clear circle red"></i></a></td>').click(function() {
                    edit_user = team;

                    $('#verify-forgot').click(function() {
                        $('#verify-form').closeModal();
                        triggerWhat = forgotSubmit;
                        $('forgot-form').openModal();
                    });

                    triggerWhat = verifySubmit;
                    $('#verify-form').openModal({
                        complete: function() {
                            edit_user = {};
                            $("#verify-password").val('');
                            $("#verify-errors").empty();
                        }
                    });
                });
                players.append($('<tr><td>'+player.real_name+'</td><td>'+player.name+'</td>'+(game.multicampus > 0 ? '<td>'+player.campus+'</td>' : '')+'<td>'+dateFormat(new Date(+player.time*1000))+'</td></tr>').append(l_edit).append(l_delete));
            });
            items.append(item.append($('<div class="collapsible-body"></div>').append(table.append(players))));
        });

        $('#entries').append(items.collapsible());
    });
};
var getSoloEntries = function(game) {
    $.get("api/entries/"+game.rowid, function(entries) {
        if (entries.length == 0) {
            return $('#entries').append('<p class="flow-text center">Aucune inscription pour le moment.</p>');
        }
        var l_edit, l_delete, items = $('<tbody></tbody>');
        $.each(entries, function(i, player) {
            l_edit = $('<td><a href="#"><i class="mdi-content-create circle blue"></i></a></td>').click(function() {
                edit_user = player;
                initializeForm();
            });
            l_delete = $('<td><a href="#"><i class="mdi-content-clear circle red"></i></a></td>').click(function() {
                edit_user = player;

                $('#verify-forgot').click(function() {
                    $('#verify-form').closeModal();
                    triggerWhat = forgotSubmit;
                    $('#forgot-form').openModal();
                });

                triggerWhat = verifySubmit;
                $('#verify-form').openModal({
                    complete: function() {
                        edit_user = {};
                        $("#verify-password").val('');
                        $("#verify-errors").empty();
                    }
                });
            });
            items.append($('<tr><td>'+player.real_name+'</td><td>'+player.name+'</td>'+(game.multicampus > 0 ? '<td>'+player.campus+'</td>' : '')+'<td>'+dateFormat(new Date(+player.time*1000))+'</td></tr>').append(l_edit).append(l_delete));
        });
        $("#entries").append('<table><thead><tr><th>Nom</th><th>'+game.nickname_field+'</th>'+(game.multicampus > 0 ? '<th>Campus</th>' : '')+'<th>Date d\'inscription</th><th></th><th></th></tr></thead></table>');
        $("#entries table").append(items);
    });
};

// Loads games
$.get("api/games", function(data) {
    $.each(data, function(i, link) {
        // Appends the games to the sidebar
        $("#slide-out").append(
            // Shows the game entries on click
            $('<li><a href="#">'+link.name+'</a></li>').click(function() {
                $(this).siblings().removeClass("active");
                $(this).addClass("active");
                // Signup button: shows the right modal on click
                $('#welcome').html($('<br/><div class="col s12"></div>'));
                $('#welcome div').append($('<button class="waves-effect waves-light btn large"><i class="mdi-social-group-add left"></i> S\'inscrire</button>').click(initializeForm));
                $('#entries').empty();
                current_game = link;
                if (link.num_players > 1)
                    getTeamEntries(link);
                else
                    getSoloEntries(link);
            })
        );
    });
});