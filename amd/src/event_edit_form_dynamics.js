/**
 * Local plugin "Totem: show teacher's attendences and event totem" - Version file
 *
 * @package    local_totem
 * @copyright  2022, Aureliano Martini (Liceo cantonale di Lugano 2) <aureliano.martini@edu.ti.ch>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define(['jquery'], function() {
    return {
        init: function(params) {
            // Event type list
            document.getElementById('id_eventtypelist').innerHTML = "";
            var option = document.createElement("option");
            option.value = '';
            option.text = '';
            document.getElementById('id_eventtypelist').add(option);

            var list = params.eventtypelist.split('\n');
            list.forEach(function(item) {
                var params = item.split('|');
                var option = document.createElement("option");
                option.value = params[0];
                option.text = params[1];
                option.style = params[2];
                document.getElementById('id_eventtypelist').add(option);
            });

            document.getElementById('id_eventtypelist').addEventListener('change', function() {
                document.getElementsByName('eventtype')[0].value = document.getElementById("id_eventtypelist").value;
            });

            if (document.getElementsByName('eventtype')[0].value != '') {
                var element = document.getElementById("id_eventtypelist");
                element.value = document.getElementsByName('eventtype')[0].value;
                element.dispatchEvent(new Event('change'));
            }

            // Teacher list
            require(['jquery', 'core/ajax', 'core/notification'], function($, ajax, notification) {
                var promises = ajax.call([
                    {
                        methodname: 'local_totem_get_userlist',
                        args: { source: parseInt(params.source + ""), sourceid: parseInt(params.sourceid + "") }
                    }
                ]);
                promises[0].done(function(response) {
                    document.getElementById('id_useridlist').innerHTML = "";
                    var option = document.createElement("option");
                    option.value = '';
                    option.text = '';
                    document.getElementById('id_useridlist').add(option);
                    response.forEach(function(item) {
                        var option = document.createElement("option");
                        option.value = item.id;
                        option.text = item.lastname + " " + item.firstname;
                        document.getElementById('id_useridlist').add(option);
                    });
                    document.getElementById('id_useridlist').addEventListener('change', function() {
                        document.getElementsByName('userid')[0].value = document.getElementById("id_useridlist").value;
                        if (document.getElementsByName('teaching')[0].value != '') {
                            document.getElementsByName('teaching')[0].value = '-';
                        }
                        var promises2 = ajax.call([
                            {
                                methodname: 'local_totem_get_teachinglist',
                                args: {
                                    blockteachings: String(params.blockteachings),
                                    teacherid: parseInt(document.getElementById("id_useridlist").value + '')
                                }
                            }
                        ]);
                        promises2[0].done(function(response2) {
                            document.getElementById('id_teachinglist').innerHTML = "";
                            var option = document.createElement("option");
                            option.value = '';
                            option.text = '';
                            document.getElementById('id_teachinglist').add(option);
                            response2.forEach(function(item) {
                                var option = document.createElement("option");
                                option.value = item.id;
                                option.text = item.name;
                                document.getElementById('id_teachinglist').add(option);
                            });
                            if (document.getElementsByName('id')[0].value == '0') {
                                if (response2.length > 0) {
                                    document.getElementsByName('teaching')[0].value = response2[0].id;
                                    document.getElementById("id_teachinglist").value = response2[0].id;
                                }
                            } else {
                                var d = document.getElementById("id_teachinglist");
                                if (document.getElementsByName('teaching')[0].value == '') {
                                    d.value = document.getElementsByName('teaching')[0].value;
                                } else {
                                    d.value = document.getElementsByName('teaching')[0].value;
                                    if (document.getElementById("id_teachinglist").value == '') {
                                        document.getElementsByName('teaching')[0].value = response2[0].id;
                                        document.getElementById("id_teachinglist").value = response2[0].id;
                                    }
                                }
                            }
                            document.getElementById('id_teachinglist').addEventListener('change', function() {
                                document.getElementsByName('teaching')[0].value = document.getElementById("id_teachinglist").value;
                            }, false);
                        }).fail(notification.exception);
                    }, false);
                    if (document.getElementsByName('userid')[0].value != '') {
                        var element = document.getElementById("id_useridlist");
                        element.value = document.getElementsByName('userid')[0].value;
                        element.dispatchEvent(new Event('change'));
                    }
                }).fail(notification.exception);
             });

            // Timetable: timestart
            document.getElementById('id_timestartlist').innerHTML = "";
            var option = document.createElement("option");
            option.value = '0';
            option.text = '';
            document.getElementById('id_timestartlist').add(option);

            var list = params.timetable.split('\n');
            list.forEach(function(item) {
                var params = item.split('|');
                var option = document.createElement("option");
                option.value = params[0];
                option.text = params[1];
                document.getElementById('id_timestartlist').add(option);
            });

            document.getElementById('id_timestartlist').addEventListener('change', function() {
                document.getElementsByName('timestart')[0].value = document.getElementById('id_timestartlist').value;
            });

            if (document.getElementsByName('timestart')[0].value != '') {
                var element = document.getElementById('id_timestartlist');
                element.value = document.getElementsByName('timestart')[0].value;
                element.dispatchEvent(new Event('change'));
            }

            document.getElementById('id_timestartlist').addEventListener('change', function() {
                var tstart = document.getElementById('id_timestartlist');
                var tend = document.getElementById('id_timeendlist');
                for (var i = 0; i < tend.options.length; i++) {
                    if (i == 0) {
                        tend.options[i].style = "";
                    } else if (i < tstart.value) {
                        tend.options[i].style = "display: none";
                    } else {
                        tend.options[i].style = "";
                    }
                }
                if (tend.value <= tstart.value) {
                    tend.value = parseInt(tstart.value);
                    tend.dispatchEvent(new Event('change'));
                }
            }, false);

            // Timetable: timeend
            document.getElementById('id_timeendlist').innerHTML = "";
            var option = document.createElement("option");
            option.value = '';
            option.text = '';
            document.getElementById('id_timeendlist').add(option);

            var list = params.timetable.split('\n');
            list.forEach(function(item) {
                var params = item.split('|');
                var option = document.createElement("option");
                option.value = params[0];
                option.text = params[2];
                document.getElementById('id_timeendlist').add(option);
            });

            document.getElementById('id_timeendlist').addEventListener('change', function() {
                document.getElementsByName('timeend')[0].value = document.getElementById("id_timeendlist").value;
            });

            if (document.getElementsByName('timeend')[0].value != '') {
                var element = document.getElementById("id_timeendlist");
                element.value = document.getElementsByName('timeend')[0].value;
                element.dispatchEvent(new Event('change'));
            }

            // Trigger Timetable timestart event
            document.getElementById('id_timestartlist').dispatchEvent(new Event('change'));

            // Message templates
            document.getElementById('id_displaytexttemplatelist').innerHTML = "";
            var option = document.createElement("option");
            option.value = '';
            option.text = '';
            document.getElementById('id_displaytexttemplatelist').add(option);

            var list = params.msgtemplates.split('\r\n');
            list.forEach(function(item) {
                var option = document.createElement("option");
                option.value = item;
                option.text = item;
                document.getElementById('id_displaytexttemplatelist').add(option);
                if (document.getElementById('id_displaytext').value.substring(0, item.length).valueOf() == item.valueOf()) {
                    document.getElementById('id_displaytext').value =
                        document.getElementById('id_displaytext').value.slice(item.length);
                    document.getElementById('id_displaytexttemplatelist').value = item;
                }
            });

            document.getElementById('id_displaytexttemplatelist').addEventListener('change', function() {
                document.getElementsByName('displaytexttemplate')[0].value =
                    document.getElementById('id_displaytexttemplatelist').value;
            });
        }
    };
});