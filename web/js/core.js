/**
 * Created by Jose on 17/05/2016.
 */
SPY = {} || SPY;

SPY.commons = {
    /* COMMONS OBJECTS */
    /* load icon*/
    loadIcon: $('#load-icon'),
    filters: [],
    calendarSource: {}
};

SPY.init = function () {
    var _loader = SPY.load;
    switch (SPY.getUrl('r')) {
        case "site/index":
        case undefined:
            _loader.core();
            _loader.siteHome();
            break;
        case "client/index":
            _loader.core();
            _loader.clientIndex();
            break;
        case "client/create":
            _loader.core();
            _loader.clientForm();
            break;
        case "client/update":
            _loader.core();
            _loader.clientForm();
            _loader.clientUpdate();
            break;
        case "client/view":
            _loader.core();
            _loader.clientView();
            break;
        case "client/massive":
            _loader.core();
            _loader.clientMassive();
            break;
        case "commercial/calendar":
            _loader.core();
            _loader.commercialCalendar();
            break;
        case "commercial/guides":
            _loader.core();
            _loader.commercialGuides();
            break;
        case "commercial/account":
            _loader.core();
            _loader.commercialAccount();
            break;
        case "commercial/files":
            _loader.core();
            _loader.commercialFiles();
            break;
        case "commercial/maps":
            _loader.core();
            _loader.commercialMaps();
            break;
        case "task/index":
            _loader.core();
            _loader.taskIndex();
            break;
        case "admin/index":
            _loader.adminCore();
            _loader.adminIndex();
            break;
        case "admin/parse":
            _loader.adminCore();
            _loader.adminParse();
            break;
        case "admin/calendar":
            _loader.adminCore();
            _loader.adminCalendar();
            break;
        case "admin/guides":
            _loader.adminCore();
            _loader.adminGuides();
            break;
        case "admin/notes":
            _loader.adminCore();
            _loader.adminNotes();
            break;
        default:
            //No actions required on another views
            break;
    }
};

SPY.getUrl = function (sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;
    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};

SPY.load = {
    core: function () {
        /* load main menu */
        SPY.prototype.effects.mainMenu();
        /* logout */
        $('#logout').click(function () {
            $.ajax({
                method: "POST",
                url: "?r=site/logout",
                async: false
            }).done(function () {
                location.reload();
            });
        });
        /* modal forms*/
        $('#menu-create-task, #shortcut-task').click(function () {
            SPY.prototype.forms.newTask();
        });
        $('#menu-create-note, #shortcut-note').click(function () {
            SPY.prototype.forms.newNote();
        });
        /* tooltips */
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
    },
    siteHome: function () {
        // switch task blocks
        $('.animated').click(function () {
            SPY.prototype.actions.switchTaskBLocks($(this));
        });
        // update task description
        $('.task-data-desc').keyup(function () {
            SPY.prototype.effects.enableDescriptionUpdate($(this));
        });
        // update task description
        $('.task-action-update').click(function () {
            SPY.prototype.actions.updateTaskDescription($(this));
        });
        // clear report text
        $('.task-report-text').focus(function () {
            SPY.prototype.effects.clearReportText($(this));
        });
        // finalize task
        $('.task-report-save').click(function () {
            SPY.prototype.actions.finalizeTask($(this));
        });
        // upload file
        $('.file-add').click(function () {
            SPY.prototype.effects.expandFileUpload($(this));
        });
        // load statistics
        $.ajax({
            method: "POST",
            url: "?r=commercial/get-clients-statistics",
            async: false
        }).done(function (data) {
            setTimeout(function () {
                Morris.Bar({
                    element: 'clients-statistics',
                    data: data,
                    resize: true,
                    xkey: 'y',
                    ykeys: ['a'],
                    labels: ['TAREAS']
                });
            }, 2000);
        });
        // load digital clock
        SPY.prototype.effects.loadClock();
    },
    clientIndex: function () {
        /* scrollBar pluging */
        $('#client-list').mCustomScrollbar();

        /* hide contact popover opened */
        var contacts = $('.contact-item');
        contacts.click(function () {
            contacts.not(this).popover('hide');
        });

        /* add/remove client to route */
        $('.client-route').click(function () {
            SPY.prototype.actions.clientRoute($(this));
        });

        /* expand client */
        $('.client-expand').click(function () {
            SPY.prototype.actions.expandClient($(this));
        });

        /* switch task blocks */
        $('.animated').click(function () {
            SPY.prototype.actions.switchTaskBLocks($(this));
        });

        /* update task description */
        $('.task-data-desc').keyup(function () {
            SPY.prototype.effects.enableDescriptionUpdate($(this));
        });

        // update task description
        $('.task-action-update').click(function () {
            SPY.prototype.actions.updateTaskDescription($(this));
        });

        // clear report text
        $('.task-report-text').focus(function () {
            SPY.prototype.effects.clearReportText($(this));
        });

        // finalize task
        $('.task-report-save').click(function () {
            SPY.prototype.actions.finalizeTask($(this), false);
        });

        // upload file
        $('.file-add').click(function () {
            SPY.prototype.effects.expandFileUpload($(this));
        });

        // create new task
        $('.client-task').click(function () {
            var clientId = $(this).parent().parent().parent().attr('key');
            SPY.prototype.forms.newTask(undefined, clientId);
        });
    },
    clientForm: function () {
        // prevent form submit en enter press
        $('#form-new-client').each(function () {
            $(this).find('input').keypress(function (e) {
                if (e.which == 10 || e.which == 13) {
                    e.preventDefault();
                }
            });
        });

        // faculty mode
        $('.switch-handle, .switch-label').click(function () {
            SPY.prototype.effects.enableFacultyMode();
        });

        // DISC selection
        var selector = $('#disc-selector'),
            colors = selector.children(),
            current = selector.attr('current'),
            inputDISC = $('#spyclient-disc');
        colors.click(function () {
            var selected = $(this),
                color = selected.css('backgroundColor');
            colors.addClass('disc-selected');
            selected.removeClass('disc-selected');
            inputDISC.val(color);
        });

        // photo selector
        var photoBtn = $('#photo-btn'),
            photoInput = $('#spyclient-photo'),
            photoPreview = $('#current-photo'),
            photoCheck = '<i class="fa fa-check" aria-hidden="true" style="color: forestgreen; font-size: 133%; padding: 0 4%;"></i>';
        photoBtn.click(function () {
            photoInput.click();
        });
        photoInput.change(function () {
            if ($(this).val()) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    photoPreview.attr('src', e.target.result);
                };
                reader.readAsDataURL(this.files[0]);
                photoPreview.hasClass('hidden') ? photoPreview.removeClass('hidden') : null;
                photoBtn.find('.fa-check').remove();
                photoBtn.append(photoCheck);
            }
        });

        // adding positions / categories / sectors / facultys* / departaments
        $('.add-type').click(function () {
            SPY.prototype.forms.addType($(this));
        });


        /* GET LOCATION DATA */
        var findPC = $('#find-postal-code'),
            findAddress = $('#find-address'),
            address = $('#spyclient-address'),
            city = $('#spyclient-city'),
            province = $('#spyclient-province'),
            postalCode = $('#spyclient-postal_code'),
            coordinates = $('#spyclient-coordinates');

        // populate client location on faculty selection
        $('#spyclient-faculty_id').change(function () {
            if (!$('faculty-mode').hasClass('hidden')) {
                var facultyID = $(this).val(),
                    coordinatesArray,
                    coordinatesObj,
                    view,
                    container = '<div id="map-container"></div>',
                    loading = '<img id="load-icon" src="img/load-icon.gif" style="position: inherit;z-index: 9999999; margin: 20.5% 38.5%; height: 75px;" />',
                    falseMap = $('#false-client-map'),
                    locationMap = $('#client-map');
                $.ajax({
                    method: "POST",
                    url: "?r=types/get-faculty-location-data",
                    async: false,
                    data: {
                        id: facultyID
                    }
                }).done(function (locationData) {
                    postalCode.val(locationData.postalCode);
                    province.val(locationData.province);
                    city.val(locationData.city);
                    address.val(locationData.address);
                    coordinates.val(locationData.coordinates);
                    coordinatesArray = locationData.coordinates.split(',');
                    coordinatesObj = [coordinatesArray[0], coordinatesArray[1]];
                    falseMap.hasClass('hidden') ? locationMap.empty() : falseMap.addClass('hidden');
                    locationMap.append(container).removeClass('hidden');
                    view = $('#map-container');
                    view.append(loading);
                    setTimeout(function () {
                        view.gmap3({
                            center: coordinatesObj,
                            zoom: 35,
                            addressControlOptions: {
                                position: 'RIGHT_TOP'
                            },
                            mapTypeId: google.maps.MapTypeId.HYBRID,
                            fullscreenControl: true,
                            fullscreenControlOptions: {
                                position: 'RIGHT_TOP'
                            },
                            zoomControl: true,
                            mapTypeControl: true,
                            scaleControl: true,
                            streetViewControl: true,
                            rotateControl: false,
                            clickToGo: true
                        }).marker({
                            position: coordinatesObj
                        });
                        view.css('position', 'inherit').find('#load-icon').remove();
                    }, 1500);
                });
            }
        });

        // auto-complete postal code
        postalCode.keyup(function (e) {
            var key = e.keyCode;
            postalCode.data("tooltip") ? postalCode.tooltip('hide') : null;
            if (key == 13) {
                SPY.prototype.actions.autoCompletePostalCode();
            }
            if (key == 8 || key == 46) {
                postalCode.tooltip('enable');
                postalCode.removeClass('has-success');
                findPC.find('i').addClass('fa-search').removeClass('fa-check');
                postalCode.attr('valid', false);
                province.attr('valid', false);
                city.attr('valid', false);
            }
        });
        postalCode.click(function () {
            if (postalCode.length != 5) {
                if (!postalCode.data('tooltip')) {
                    postalCode.tooltip('show');
                    setTimeout(function () {
                        postalCode.tooltip('hide');
                    }, 2500);
                }
            }
        });
        findPC.click(function (e) {
            if ($(this).val().length == 5) {
                e.preventDefault();
            }
            SPY.prototype.actions.autoCompletePostalCode();
        });

        // get coordinates of address
        findAddress.click(function () {
            SPY.prototype.actions.populateClientMap(false);
        });
        address.keyup(function (e) {
            if (e.keyCode == 13) {
                SPY.prototype.actions.populateClientMap(false);
            } else if (e.keyCode == 8) {
                coordinates.attr('valid', false);
            }
        });
    },
    clientUpdate: function () {
        var disc_input = $('#spyclient-disc'),
            coordinates = $('#spyclient-coordinates'),
            view;
        // render disc if selected
        if (disc_input.val() != "") {
            $('#disc-selector').find('div').each(function (idx, disc) {
                $(disc).css('backgroundColor') != disc_input.val()
                    ? $(disc).addClass('disc-selected')
                    : null;
            });
        }
        // load map on expand location
        $('#location').click(function () {
            if (coordinates.val() != "") {
                var container = '<div id="map-container"></div>',
                    loadIcon = '<img id="load-icon" src="img/load-icon.gif" style="position: inherit;z-index: 9999999; margin: 20.5% 38.5%; height: 75px;" />',
                    coordinatesArray = coordinates.val().split(','),
                    coordinatesObj = [coordinatesArray[0], coordinatesArray[1]],
                    falseMap = $('#false-client-map'),
                    map = $('#client-map');
                falseMap.hasClass('hidden') ? map.empty() : falseMap.addClass('hidden');
                map.append(container).removeClass('hidden');
                view = $('#map-container');
                view.append(loadIcon);
                setTimeout(function () {
                    view.gmap3({
                        center: coordinatesObj,
                        zoom: 30,
                        mapTypeId: google.maps.MapTypeId.HYBRID,
                        mapTypeControl: true,
                        addressControlOptions: {
                            position: 'LEFT_TOP'
                        },
                        mapTypeControlOptions: {
                            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                        },
                        navigationControl: true,
                        scrollWheel: true,
                        streetViewControl: true,
                        fullScreenControl: true,
                        fullScreenControlOptions: {
                            position: 'RIGHT_TOP'
                        }
                    }).marker({
                        position: coordinatesObj
                    });
                    view.css('position', 'inherit').find('#load-icon').remove();
                }, 1500);
            }
        });
    },
    clientView: function () {
        /* show and create contacts */
        var contacts = $('.contact-item'),
            header = $('#client-header'),
            client_map = $('#client-map');
        // contact expand and connect set main button
        contacts.click(function () {
            contacts.not(this).popover('hide');
            /* connect set contact main*/
            $('.set-as-contact-main').click(function () {
                SPY.prototype.actions.setAsContactMain($(this));
            });
        });
        /* switch task blocks */
        $('.animated').click(function () {
            SPY.prototype.actions.switchTaskBLocks($(this));
        });
        /* update task description */
        $('.task-data-desc').keyup(function () {
            SPY.prototype.effects.enableDescriptionUpdate($(this));
        });
        // update task description
        $('.task-action-update').click(function () {
            SPY.prototype.actions.updateTaskDescription($(this));
        });
        // clear report text
        $('.task-report-text').focus(function () {
            SPY.prototype.effects.clearReportText($(this));
        });
        // finalize task
        $('.task-report-save').click(function () {
            SPY.prototype.actions.finalizeTask($(this));
        });
        // upload file
        $('.file-add').click(function () {
            SPY.prototype.effects.expandFileUpload($(this));
        });
        //update client
        $('#client-update').click(function () {
            location.href = "?r=client/update&id=" + header.attr('key');
        });
        // add/remove of route array
        $('#client-add-to-route').click(function () {
            var btn = $(this),
                coordinates = header.attr('coords'),
                client = header.attr('key'),
                name = $('#client-name').text(),
                url,
                t,
                data,
                text;
            if (!coordinates) {
                alertify.error('No hay datos de geolocalizaci\u00f3n para este cliente');
            } else {
                if (!btn.hasClass('added')) {
                    url = "?r=client/add-to-route";
                    text = " <strong> a\u00f1adido</strong> a tu ruta";
                    t = true;
                }
                else {
                    url = "?r=client/delete-of-route";
                    text = " <strong> eliminado</strong> de la ruta";
                    t = false;
                }
                $.ajax({
                    method: "POST",
                    url: url,
                    data: {
                        id: client
                    }
                }).done(function (confirm) {
                    if (confirm == 1) {
                        t ? btn.addClass('added') : btn.removeClass('added');
                        alertify.warning(name + text);
                    }
                });
            }
        });
        // create task
        $('#client-new-task').click(function () {
            SPY.prototype.forms.newTask(undefined, header.attr('key'));
        });
        // add contact
        $('#client-new-contact').click(function () {
            SPY.prototype.forms.newContact();
        });
        // load map if exist
        if (client_map.length == 1) {
            var client_coordinates = client_map.attr('coords'),
                coordinatesA = client_coordinates.split(', '),
                coordinatesObj = [coordinatesA[0], coordinatesA[1]];
            client_map.gmap3({
                center: coordinatesObj,
                zoom: 23,
                mapTypeId: google.maps.MapTypeId.HYBRID,
                mapTypeControl: true,
                addressControlOptions: {
                    position: 'LEFT_TOP'
                },
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                },
                navigationControl: true,
                scrollWheel: true,
                streetViewControl: true,
                fullScreenControl: true,
                fullScreenControlOptions: {
                    position: 'RIGHT_TOP'
                }
            }).marker({
                position: coordinatesObj
            });
        }
        // expand comment
        $('#show-comment').click(function () {
            var comment = $('#comment-text').text();
            comment == "" ? comment = "Nada para mostrar" : null;
            alertify.alert().setting({
                'title': "Spy | Comentario",
                'message': comment,
                'default': "",
                'label': "CERRAR",
                'movable': false,
                'modal': true,
                'closable': false,
                'transition': 'zoom'
            }).show();
        });
        // expand equipment
        $('#show-equipment').click(function () {
            var equipment = $('#equipment-text').text();
            equipment == "" ? equipment = "Nada para mostrar" : null;
            alertify.alert().setting({
                'title': "Spy | Equipamiento",
                'message': equipment,
                'default': "",
                'label': "CERRAR",
                'movable': false,
                'modal': true,
                'closable': false,
                'transition': 'zoom'
            }).show();
        });
        // expand photo
        $('#show-photo').click(function () {
            var image = '<img class="img-resposive" src="' + $('#photo-img').attr('src') + '" style="max-width: 100%;"/>';
            alertify.alert().setting({
                'title': "Spy | Foto",
                'message': image,
                'default': "",
                'label': "CERRAR",
                'movable': false,
                'modal': true,
                'closable': false,
                'transition': 'zoom'
            }).show();
        });
        // get data from server and load statistics
        $.ajax({
            method: "POST",
            url: "?r=client/get-statistics",
            async: false,
            data: {
                id: header.attr('key')
            }
        }).done(function (data) {
            setTimeout(function () {
                Morris.Donut({
                    element: 'statistics',
                    data: data.types,
                    colors: data.colors,
                    resize: true,
                    formatter: function (x, data) {
                        return data.formatted;
                    }
                });
            }, 2000);
        });
    },
    clientMassive: function () {
        /* MASSIVE CREATION */
        var _form = $('#massive-form'),
            m_sector = $('#sector-massive-selector'),
            m_validate = $('#validate-clients'),
            m_new_sector = $('#create-sector'),
            formLine = "<div class=\"col-xs-12 formLine\"><div class=\"col-xs-3\"><input class=\"form-control clientName\" placeholder=\"Nombre de la empresa\"/></div><div class=\"col-xs-2\"><input class=\"form-control clientContact\" placeholder=\"Persona de contacto\" /></div><div class=\"col-xs-2\"><input class=\"form-control clientPhone\" maxlength=\"9\" placeholder=\"Tel&eacute;fono\" /></div><div class=\"col-xs-3\"><input class=\"form-control clientEmail\" type=\"email\" placeholder=\"Email\" /></div><div class=\"col-xs-2 lineReport\"></div><div class=\"clearfix\"></div></div>";
        _form.append(formLine);
        // validate clients
        m_validate.click(function () {
            validateClients();
        });
        // create sector
        m_new_sector.click(function () {
            createSectorModal();
        });
        //keydown on las field
        $('.clientEmail').keydown(function (e) {
            addMassiveLine(e);
        });

        function validateClients() {
            var clients = _form.find('.formLine'),
                lastLine = clients.last(),
                report = null,
                valid = null,
                _validation = SPY.prototype.validation;
            if (m_sector.val() != "") {
                if (_validation.isEmpty(lastLine.find('.clientName')) &&
                    _validation.isEmpty(lastLine.find('.clientContact')) &&
                    _validation.isEmpty(lastLine.find('.clientPhone'))) {
                    lastLine.remove();
                }
                clients.each(function (idx, item) {
                    valid = validateMassiveClient($(item));
                    if (valid) {
                        var name = $(item).find('.clientName'),
                            contact = $(item).find('.clientContact'),
                            phone = $(item).find('.clientPhone'),
                            mail = $(item).find('.clientEmail');
                        $.ajax({
                            method: "POST",
                            url: "?r=client/massive-creation",
                            async: false,
                            data: {
                                name: name.val(),
                                contact: contact.val(),
                                phone: phone.val(),
                                mail: mail.val(),
                                sector: m_sector.val()
                            }
                        }).done(function (response) {
                            processLine($(item), response);
                        });
                    } else {
                        report = $(item).find('.lineReport');
                        report.empty().append("<div class=\"invalid\">Datos no válidos</div>");
                    }
                });
                setTimeout(function () {
                    if (!_form.find('.formLine').length > 0) {
                        _form.append(formLine);
                        $('.clientEmail').keydown(function (e) {
                            addMassiveLine(e);
                        });
                    }
                }, 1750);
            } else {
                alertify.error("Selecciona un sector");
            }

            function validateMassiveClient(line) {
                var name = line.find('.clientName'),
                    contact = line.find('.clientContact'),
                    phone = line.find('.clientPhone'),
                    mail = line.find('.clientEmail'),
                    nameTest = false,
                    contactTest = true,
                    phoneTest = false,
                    mailTest = true,
                    _validate = SPY.prototype.validation;
                !_validate.isEmpty(name) ? nameTest = true : null;
                _validate.isEmpty(contact) ? contact.val('Sin definir') : null;
                !_validate.isEmpty(phone) ? phoneTest = _validate.isValidPhone(phone) : null;
                !_validate.isEmpty(mail) ? mailTest = _validate.isValidMail(mail) : null;
                !nameTest ? name.css('background', '#FFAAAA') : name.css('background', 'white');
                !contactTest ? contact.css('background', '#FFAAAA') : contact.css('background', 'white');
                !phoneTest ? phone.css('background', '#FFAAAA') : phone.css('background', 'white');
                !mailTest ? mail.css('background', '#FFAAAA') : mail.css('background', 'white');
                return (nameTest && contactTest && phoneTest && mailTest);
            }

            function processLine(line, data) {
                var report = line.find('.lineReport'),
                    link = null,
                    content = null,
                    clear = null;
                if (typeof(data) === "boolean") {
                    if (data) {
                        line.css('background-color', '#8DCF8A');
                        setTimeout(function () {
                            line.remove();
                        }, 1500);
                    }
                }
                else {
                    line.css('backgroundColor', 'CC6672');
                    clear = "<div class=\"col-xs-6 btn btn-default clean-line\"> Limpiar </div>";
                    if (data.owner) {
                        link = '<div class="col-xs-6 btn btn-danger reportInfo" owner="true" commercial="' + data.commercial + '" phone="' + data.client.phone + '" name="' + data.client.name + '" clientId="' + data.client.id + '">Info</div>';
                    } else {
                        link = '<div class="col-xs-6 btn btn-danger reportInfo" commercial="' + data.commercial + '" phone="' + data.client.phone + '" name="' + data.client.name + '">Info</div>';
                    }
                    report.empty().append(link).append(clear);
                    $('.reportInfo').click(function () {
                        var commercial = $(this).attr('commercial'),
                            phone = $(this).attr('phone'),
                            name = $(this).attr('name'),
                            clientID = $(this).attr('clientId'),
                            owner = $(this).attr('owner'),
                            message;
                        owner
                            ? message = 'El tel\u00e9fono <strong>' + phone + '</strong> ya esta registrado a nombre de <strong>' + name + '</strong><a href="?r=client/view&id=' + clientID + '" target="_blank"><div class="col-xs-4 col-xs-offset-4 btn btn-default">Ver</div></a>'
                            : message = "<strong>" + commercial + "</strong> ya tiene registrado este n\u00famero <strong>" + phone + "</strong> como " + name + "</div>";
                        alertify.alert().setting({
                            'title': "Spy | Masivo Clientes",
                            'message': message,
                            'default': "",
                            'label': "CERRAR",
                            'movable': false,
                            'modal': true,
                            'closable': false
                        }).show();
                    });
                    $('.clean-line').click(function () {
                        $(this).parent().parent().remove();
                        if (lastLineUsed()) {
                            _form.append(formLine);
                            $('.clientEmail').keydown(function (e) {
                                addMassiveLine(e);
                            });
                        }
                    });
                }
            }
        }

        function lastLineUsed() {
            return _form.find('.formLine').last().find('.clientName').val() != "";
        }

        function addMassiveLine(e) {
            if (e.keyCode == 13 || e.keyCode == 40 || e.keyCode == 9) {
                if (e.keyCode == 9) e.preventDefault();
                if (lastLineUsed()) {
                    _form.append(formLine);
                    $('.clientEmail').keydown(function (e) {
                        addMassiveLine(e);
                    });
                }
                _form.find('.formLine').last().find('.clientName').focus();
            }
        }

        function createSectorModal() {
            var prompt = alertify.prompt().setting({
                'title': "Spy | Gesti\u00f3n de sectores",
                'message': "Escribe el nombre del nuevo sector:",
                'default': "",
                'labels': {
                    ok: "A&ntilde;adir  sector",
                    cancel: "Cancelar"
                },
                'movable': false,
                'modal': true,
                'closable': false,
                'transition': 'zoom',
                'onok': function (evt, value) {
                    var input = $('.ajs-input');
                    if (value.length > 0) {
                        input.hasClass('type-fail') ? input.removeClass('type-fail') : null;
                        $.ajax({
                            method: "POST",
                            url: "?r=types/create",
                            async: false,
                            data: {
                                type: 'sector',
                                name: value
                            }
                        }).done(function (data) {
                            if (data.validate) {
                                prompt.destroy();
                                $('<option>', {
                                    value: data.item.id,
                                    text: data.item.name
                                }).appendTo('#sector-massive-selector');
                                alertify.notify(info.confirm + "<strong>" + data.item.name + "</strong>", 'success', 5);
                            }
                            else {
                                prompt.destroy();
                                setTimeout(function () {
                                    alertify.notify("Ya existe <strong>" + data.exist[0].name + "</strong>", 'error', 5);
                                }, 750);
                            }
                        });
                    }
                    else {
                        input.addClass('type-fail');
                    }
                    return false;
                }
            }).show();
        }
    },
    commercialCalendar: function () {
        // load full calendar
        var legend = $('#colors-legend'),
            events = [],
            type = null,
            _forms = SPY.prototype.forms;
        setTimeout(function () {
            var note = '<div class="col-xs-6 btn calendar-type-note" style="background:rgb(209, 209, 63)" data-toggle="tooltip" data-placement="bottom" title="Notas personales"><div class="btn btn-default calendar-toggle-type type-note"></div><img src="img/note-icon.png" class="img-responsive" /></div>';
            var sharedNote = '<div class="col-xs-6 btn calendar-type-note-shared" style="background:rgb(115, 156, 255)" data-toggle="tooltip" data-placement="bottom" title="Notas compartidas"><div class="btn btn-default calendar-toggle-type type-note"></div><img src="img/note-shared-icon.png" class="img-responsive" /></div>';
            legend.append(note);
            legend.append(sharedNote);
            $.ajax({
                method: "POST",
                url: "?r=commercial/populate-calendar",
                async: false
            }).done(function (data) {
                events = data.calendar;
                // render types buttons
                data.taskTypes.forEach(function (taskType) {
                    var type;
                    if (taskType.name == "envio de mail") {
                        type = '<div class="col-xs-12 btn calendar-type-task" style="background:' + taskType.color + '" data-toggle="tooltip" data-placement="bottom" title="envio de mail"><div class="btn btn-default calendar-toggle-type type-task" key="' + taskType.taskT_id + '"></div><div class="calendar-type-name">E-mail</div></div>';
                    }
                    else if (taskType.name == "servicio técnico") {
                        type = '<div class="col-xs-12 btn calendar-type-task" style="background:' + taskType.color + '" data-toggle="tooltip" data-placement="bottom" title="servicio técnico"><div class="btn btn-default calendar-toggle-type type-task" key="' + taskType.taskT_id + '"></div><div class="calendar-type-name">S. T&eacute;cnico</div></div>';
                    }
                    else if (taskType.name == "llamada de cortesía mensual") {
                        type = '<div class="col-xs-12 btn calendar-type-task" style="background:' + taskType.color + '" data-toggle="tooltip" data-placement="bottom" title="llamada de cortesía mensual"><div class="btn btn-default calendar-toggle-type type-task" key="' + taskType.taskT_id + '"></div><div class="calendar-type-name">LL.C.M</div></div>';
                    }
                    else {
                        type = '<div class="col-xs-12 btn calendar-type-task" style="background:' + taskType.color + '"><div class="btn btn-default calendar-toggle-type type-task" key="' + taskType.taskT_id + '"></div><div class="calendar-type-name">' + taskType.name + '</div></div>';
                    }
                    legend.append(type);
                });
                // load full calendar pluging and connect events
                $('#calendar-main').fullCalendar({
                    lang: 'es',
                    header: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'month,agendaWeek,agendaDay'
                    },
                    defaultDate: $.now(),
                    eventLimit: true,
                    events: data.calendar,
                    eventClick: function (callEvent, jsEvent, view) {
                        callEvent.note ? _forms.noteViewer(callEvent) : _forms.taskViewer(callEvent);
                    },
                    dayClick: function (date, jsEvent, view) {
                        var d = date._d,
                            year = d.getFullYear(),
                            month = d.getMonth() + 1,
                            day = d.getDate();
                        month <= 9 ? month = "0" + month : null;
                        day <= 9 ? day = "0" + day : null;
                        _forms.newTask(year + "-" + month + "-" + day);
                    },
                    eventRender: function (event, element, view) {
                        return SPY.commons.filters.indexOf(event.backgroundColor) == -1;
                    }
                });
                var types = $('.calendar-type-task');
                // set types as disabled if no events exists
                types.each(function (idx, item) {
                    var color = $(item).css('backgroundColor'),
                        cnt = 0;
                    events.forEach(function (event) {
                        event.backgroundColor == color ? cnt++ : null;
                    });
                    if (cnt == 0) {
                        var toggleButton = $(item).find('.calendar-toggle-type');
                        toggleButton.css('backgroundColor', 'rgb(255, 0, 0)');
                        toggleButton.css('pointer-events', 'none')
                    }
                });
                // toggle calendar type´s visibility
                types.click(function () {
                    SPY.prototype.effects.toggleCalendarType($(this));
                });
                // toggle calendar notes´s visibility
                $('.calendar-type-note, .calendar-type-note-shared').click(function () {
                    SPY.prototype.effects.toggleCalendarType($(this));
                });
                $('#colors-legend').find('.col-xs-6').tooltip();
                types.tooltip();
            });
        }, 500);

    },
    commercialGuides: function () {
        $('.guide-line').click(function () {
            SPY.prototype.effects.slideGuide($(this));
        });
    },
    commercialAccount: function () {
        $('#expand-passwords').click(function () {
            var form = $('#passwords-form');
            !form.is(':visible') ? form.slideDown() : form.slideUp();
        })
        $('#update-password').click(function () {
            SPY.prototype.actions.updatePassword();
        });
    },
    commercialFiles: function () {
        $('.file-remove').click(function () {
            SPY.prototype.actions.removeFile($(this), true);
        });
    },
    commercialMaps: function () {
        var map = $('#map'),
            findRoute = $('#find-route'),
            shopLocation = $('#exit-home'),
            clientList = $('#destination-multiple'),
            exitInput = $('#exit-point'),
            destinationInput = $('#destination-point');
        map.gmap3({
            center: [37.15313133308989, -3.5919255670584107],
            zoom: 17,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        findRoute.click(function () {
            SPY.prototype.actions.createRoute();
        });
        $('#destination-multiple, #exit-home').tooltip();
        shopLocation.click(function () {
            if (!exitInput.val()) {
                shopLocation.css('backgroundColor', 'orange');
                exitInput.val(shopLocation.attr('coordinate-x') + ", " + shopLocation.attr('coordinate-y'));
            } else {
                shopLocation.css('backgroundColor', '#fff');
                exitInput.val("");
            }
        });
        destinationInput.keydown(function(e){
            if(e.keyCode == 13){
               SPY.prototype.actions.createRoute();
            }
        });
        clientList.click(function(){
            var btn = $(this),
                icon = btn.find('i');
           if(icon.hasClass('fa-list')){
                destinationInput.attr('disabled', true).val("");
                icon.removeClass('fa-list').addClass('fa-caret-square-o-right');
                SPY.prototype.actions.populateMapClients(false);
           } else {
               destinationInput.attr('disabled', false);
               icon.removeClass('fa-caret-square-o-right').addClass('fa-list');
               SPY.prototype.actions.populateMapClients(true);
           }
        });
    },
    taskIndex: function () {
        var dateSelector = $('#task-date-search'),
            _validate = SPY.prototype.validation;
        // load filter datepicker
        dateSelector.datepicker({
            'format': 'yyyy-mm-dd',
            'autoclose': true,
            'weekStart': '1'
        });
        // prevent submit if not date selected
        $('#search-for-date').click(function (e) {
            if (_validate.isEmpty(dateSelector)) {
                e.preventDefault();
                alertify.error("Selecciona primera una fecha");
            }
        });
        // clear filters
        $('#clear-filters').click(function () {
            location.href = "?r=task/index";
        });
        // expand task info
        $('.task-list-expand .btn').click(function () {
            var item = $(this).parent().parent().parent(),
                data = item.find('.task-list-expanded'),
            /* close others */
                AllDetails = $('.task-list-item').find('.task-list-expanded');
            AllDetails.not(this).each(function (idx, obj) {
                $(obj).slideUp('slow', function () {
                    $(obj).addClass('task-detail-close').removeClass('task-detail-open');
                });
            });
            /* open/close details */
            if (data.hasClass('task-detail-close')) {
                data.slideDown('slow', function () {
                    data.addClass('task-detail-open').removeClass('task-detail-close');
                });
            } else {
                data.slideUp('slow', function () {
                    data.addClass('task-detail-close').removeClass('task-detail-open');
                });
            }
        });
        // toggle views on task expanded
        $('.task-list-expanded-actions .btn').click(function () {
            SPY.prototype.effects.toggleTaskView($(this));
        });
        // update description
        $('.task-update-text').click(function () {
            var btn = $(this),
                description = btn.parent().find('textarea');
            description.val() == "Escribe un reporte si es necesario.(OPCIONAL)" ? description.html("") : null;
            $.ajax({
                method: "POST",
                url: "?r=task/update-desc",
                data: {
                    id: btn.attr('key'),
                    text: description.val()
                }
            }).done(function (confirm) {
                if (confirm == 1) {
                    description.css('backgroundColor', 'lightgrey');
                    btn.addClass('disabled');
                }
            });
        });
        // enable task description update
        $('.task-list-description').find('textarea').keyup(function () {
            var currentColor = $(this).css('backgroundColor'),
                btn = $(this).parent().find('.task-update-text');
            currentColor != "white" ? $(this).css('backgroundColor', 'white') : null;
            btn.hasClass('disabled') ? btn.removeClass('disabled') : null;
        });
        // clear report text
        $('.task-list-report').find('textarea').click(function () {
            SPY.prototype.effects.clearReportText($(this));
        });
        // finalize task
        $('.task-list-finalize').click(function () {
            var taskId = $(this).attr('key'),
                report = $(this).parent().find('textarea');
            $.ajax({
                method: "POST",
                url: "?r=task/finalize",
                data: {
                    id: taskId,
                    report: report.val()
                }
            }).done(function (confirm) {
                if (confirm == 1) {
                    setTimeout(function () {
                        location.reload();
                    });
                }
            });
        });
    },
    adminCore: function () {
        /* load main menu */
        SPY.prototype.effects.mainMenu();
        /* logout */
        $('#logout').click(function () {
            $.ajax({
                method: "POST",
                url: "?r=site/logout",
                async: false
            }).done(function () {
                location.reload();
            });
        });
        /* tooltips */
        $('[data-toggle="tooltip"]').tooltip();
        $('[data-toggle="popover"]').popover();
        // open new commercial form
        $('#add-commercial').click(function () {
            SPY.prototype.forms.newCommercial();
        });
        // load shared note form
        $('#new-shared-note').click(function () {
            SPY.prototype.forms.newSharedNote();
        });
        // show commercials data
        $('#show-commercials').click(function(){
           SPY.prototype.actions.showCommercials();
        });
    },
    adminIndex: function () {
        $('#commercial-selector').change(function () {
            SPY.prototype.actions.loadCommercialFullData($(this).val());
        });
        $('#opened-list').mCustomScrollbar();
        $('#closed-list').mCustomScrollbar();
    },
    adminCalendar: function () {
        $('#admin-calendar').fullCalendar({
            lang: 'es',
            header: {
                left: 'prev,next today',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            defaultDate: $.now(),
            eventLimit: true,
            events: {},
            eventClick: function (callEvent, jsEvent, view) {
                // get data from server
                $.ajax({
                    method: "POST",
                    url: "?r=task/get-full-task",
                    data: {
                        id: callEvent.id
                    }
                }).done(function (fullTask) {
                    renderTask(fullTask, function (content) {
                        // render form and get html code
                        alertify.alert().setting({
                            'title': "Spy | Visor de tareas",
                            'message': content,
                            'default': "",
                            'label': 'CERRAR',
                            'movable': false,
                            'modal': true,
                            'closable': false,
                            'transition': 'zoom'
                        }).show();
                    });
                });
            }
        });
        function renderTask(task, rendered) {
            var datetimeA = task.alert.split(" "),
                dateA = datetimeA[0].split("-"),
                timeA = datetimeA[1].split(":"),
                formattedDate = dateA[2] + "-" + dateA[1] + "-" + dateA[0],
                formattedTime = timeA[0] + ":" + timeA[1];
            var source = '<div class="col-xs-12 task-viewer">' +
                '<div class="col-xs-3 task-title">Cliente:</div><div class="col-xs-9 task-client">' + task.client + '</div>' +
                '<div class="col-xs-3 task-title">Asunto:</div><div class="col-xs-9 task-subject">' + task.subject + '</div>' +
                '<div class="col-xs-3 task-title">Hora:</div><div class="col-xs-9 task-datetime">' + formattedTime + "  " + formattedDate + '</div>' +
                '<div class="col-xs-3 task-title">Tipo:</div><div class="col-xs-9 task-type-name" style="background:' + task.typeColor + '">' + task.type + '</div>' +
                '<textarea class="form-control task-description" readonly>' + task.description + '</textarea>' +
                '</div>';
            return rendered(source);
        }

        $('#commercial-selector').change(function () {
            SPY.prototype.actions.loadCommercialTasks($(this).val());
        });
    },
    adminGuides: function () {
        $('#add-guide').click(function () {
            SPY.prototype.forms.newGuide();
        });
        $('.guide-line').click(function () {
            SPY.prototype.effects.slideGuide($(this));
        });
        $('.guide-text').keyup(function () {
            SPY.prototype.effects.enableGuideUpdate($(this));
        });
        $('.guide-save').click(function () {
            SPY.prototype.actions.updateGuide($(this));
        });
        $('.file-remove').click(function () {
            SPY.prototype.actions.removeFile($(this));
        });
        $('.delete-guide').click(function() {
            SPY.prototype.actions.deleteGuide($(this));
        })
        $('.guide-dropzone').dropzone();
    },
    adminParse: function(){
        $('[type="submit"]').click(function(e){
           if(!$('#clientsparser-file').val() && $('#clientsparser-commercial').val()){
                e.preventDefault();
                alertify.error("Elige un archivo");
            }
        });
    },
    adminNotes: function(){
        $('.note-text').keyup(function(){
            SPY.prototype.effects.enableUpdateNote($(this));
        });
        $('.note-finalize').click(function(){
            SPY.prototype.actions.finalizeNote($(this));
        });
        $('.note-delete').click(function(){
            SPY.prototype.actions.deleteNote($(this));
        });
        $('.note-update').click(function(){
            SPY.prototype.actions.updateNote($(this));
        });

    }
};

SPY.prototype = {
    validation: {
        isEmpty: function (input) {
            return input.val().trim().length === 0;
        },
        isValidMail: function (mail) {
            var pattern = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
            return pattern.test(mail.val().trim());
        },
        isValidPhone: function (phone) {
            var pattern = /\b\d{3}[-.]?\d{3}[-.]?\d{3}\b/;
            return pattern.test(phone.val().trim());
        }
    },
    forms: {
        // add types
        addType: function (element) {
            var type = element.attr('key'),
                select = element.parent().find('.field-input select'),
                info = {};
            switch (type) {
                case "position":
                    info.title = "Gesti\u00f3n de cargos";
                    info.name = "cargo";
                    info.message = "Escribe la denominaci\u00f3n del nuevo cargo:";
                    info.confirm = "Nuevo cargo a\u00f1adido: ";
                    break;
                case "category":
                    info.title = "Gesti\u00f3n de categor\u00edas";
                    info.name = "categor\u00eda";
                    info.message = "Escribe el nombre de la nueva categor\u00eda:";
                    info.confirm = "Nueva categor\u00eda a\u00f1adida: ";
                    break;
                case "sector":
                    info.title = "Gesti\u00f3n de sectores";
                    info.name = "sector";
                    info.message = "Escribe el nombre del nuevo sector:";
                    info.confirm = "Nuevo sector a\u00f1adido: ";
                    break;
                case "faculty":
                    info.title = "Gesti\u00f3n de facultades";
                    info.name = "facultad";
                    info.message = "Escribe el nombre de la nueva facultad:";
                    info.confirm = "Nueva facultad a\u00f1adida: ";
                    break;
                case "department":
                    info.title = "Gesti\u00f3n de departamentos";
                    info.name = "departamento";
                    info.message = "Escribe el nombre del nuevo departamento:";
                    info.confirm = "Nuevo departamento a\u00f1adido: ";
                    break;
            }
            if (type == "faculty") {
                SPY.prototype.forms.newFaculty(info);
            } else {
                var prompt = alertify.prompt().setting({
                    'title': "Spy | " + info.title,
                    'message': info.message,
                    'default': "",
                    'labels': {
                        ok: "A&ntilde;adir " + info.name,
                        cancel: "Cancelar"
                    },
                    'movable': false,
                    'modal': true,
                    'closable': false,
                    'transition': 'zoom',
                    'onok': function (evt, value) {
                        var input = $('.ajs-input');
                        if (value.length > 0) {
                            input.hasClass('type-fail') ? input.removeClass('type-fail') : null;
                            $.ajax({
                                method: "POST",
                                url: "?r=types/create",
                                async: false,
                                data: {
                                    type: type,
                                    name: value
                                }
                            }).done(function (data) {
                                if (data.validate) {
                                    prompt.destroy();
                                    select.append($('<option>', {
                                        value: data.item.id,
                                        text: data.item.name,
                                        selected: "selected"
                                    }));
                                    alertify.notify(info.confirm + "<strong>" + data.item.name + "</strong>", 'success', 5);
                                } else {
                                    prompt.destroy();
                                    alertify.notify("Ya existe <strong>" + data.exist[0].name + "</strong>", 'error', 5);
                                }
                            });
                        } else {
                            input.addClass('type-fail');
                        }
                        return false;
                    }
                }).show();
            }
        },
        // add faculty
        newFaculty: function (info, fData, locationObj) {
            var source,
                name = "",
                address = "",
                coordinates = "",
                facultyName,
                facultyAddress,
                dataURL = 'img/static-granada-map.jpg';
            if (fData != undefined) {
                if (locationObj != undefined) {
                    name = fData.name;
                    locationObj.number != undefined ? address = locationObj.street + ", " + locationObj.number : address = locationObj.street + ", s/n";
                    coordinates = locationObj.formattedCoordinates;
                    var facultyMap = $('#faculty-map');
                    dataURL = "https://maps.googleapis.com/maps/api/staticmap?scale=1&size=400x300&center=" + coordinates + "&zoom=18&maptype=hybrid" + "&visual_refresh=true&markers=size:large%7Ccolor:0xff151c%7Clabel:F%7C" + coordinates;
                    facultyMap.attr('src', dataURL);
                } else {
                    name = fData.name;
                }
            }
            source = '<div id="faculty" class="container-fluid">' +
                '<div class="form-group col-xs-12"><label for="faculty-name" class="col-md-3 col-xs-12 field-title">Nombre</label><input id="faculty-new-name" type="text" class="col-md-9 col-xs-12 form-control field-input" value="' + name + '" /></div>' +
                '<div class="form-group col-xs-12"><label for="faculty-address" class="col-md-3 col-xs-12 field-title">Direcci&oacute;n</label><input id="faculty-address" type="text" class="col-md-8 col-xs-11 form-control field-input" value="' + address + '" /><div id="faculty-find-address" class="btn btn-default col-xs-1"><i class="fa fa-search"></i></div></div>' +
                '<div class="form-group col-xs-12"><label id="faculty-map-label" class="col-md-3 col-xs-12 field-title">Ubicaci&oacute;n</label><img id="faculty-map" class="img-responsive" src="' + dataURL + '" /></div>' +
                '<input id="faculty-coordinates" class="hidden" type="text" valid="false" value="' + coordinates + '"/>' +
                '</div>';
            var form = alertify.confirm().setting({
                'title': "Spy | " + info.title,
                'message': source,
                'default': "",
                'labels': {
                    ok: "A&ntilde;adir " + info.name,
                    cancel: "Cancelar"
                },
                'movable': false,
                'modal': true,
                'closable': false,
                'transition': 'zoom',
                'onok': function (evt, value) {
                    var coordinates = $('#faculty-coordinates'),
                        addressInput = $('#faculty-address');
                    if (coordinates.val() && coordinates.attr('valid') == "true") {
                        $.ajax({
                            method: "POST",
                            url: "?r=types/create-faculty",
                            data: {
                                'name': name,
                                'address': address,
                                'city': locationObj.city,
                                'province': locationObj.province,
                                'postalCode': locationObj.postalCode,
                                'coordinates': locationObj.formattedCoordinates

                            }
                        }).done(function (faculty) {
                            var select = $('#spyclient-faculty_id');
                            select.append($('<option>', {
                                value: faculty.item.id,
                                text: faculty.item.name,
                                selected: "selected"
                            }));
                        });
                        setTimeout(function () {
                            alertify.success("FACULTAD <b>" + name + "</b> a&ntilde;adida correctamente");
                        }, 750);
                    } else {
                        addressInput.css('border-Color', 'rgb(90, 21, 21)');
                        return false;
                    }
                }
            }).show();
            if (fData != undefined) {
                $('#faculty-coordinates').attr('valid', true);
            }

            $('#faculty-address').keydown(function (e) {
                if (e.keyCode == 13) {
                    findFacultyAddress();
                } else if (e.keyCode == 8) {
                    $('#faculty-coordinates').attr('valid', "false");
                }
            });
            $('#faculty-find-address').click(function () {
                findFacultyAddress();
            });

            function findFacultyAddress(fData) {
                if (fData == undefined) {
                    facultyName = $('#faculty-new-name').val();
                    facultyAddress = $('#faculty-address').val();
                    form.destroy();
                    SPY.commons.loadIcon.removeClass('hidden');
                    setTimeout(function () {
                        SPY.commons.loadIcon.addClass('hidden');
                        SPY.prototype.actions.populateClientMap(true, {
                            'name': facultyName,
                            'address': facultyAddress
                        });
                    }, 1500);
                }
            }
        },
        // add task
        newTask: function (fullDate, client) {
            var form = '<div id="modal-new-task-form">' +
                '<div class="form-group col-xs-12"><label for="task-client">Cliente</label><select id="task-client" class="form-control"><option disabled="disabled" selected="selected">Selecciona un cliente</option></select></div>' +
                '<div class="form-group col-xs-12"><div id="task-type" class="dropdown"><button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown">Tipo de tarea  <span class="caret"></span></button><ul class="dropdown-menu"></ul></div><div id="task-type-selected"></div></div>' +
                '<div class="form-group col-xs-12"><label for="task-subject" class="col-xs-3">Asunto</label><input id="task-subject" class="col-xs-9 form-control" value="" placeholder="Breve descripci&oacute;n"/></div>' +
                '<div class="form-group col-xs-12"><label for="task-text" class="hidden">Descripci</label><textarea id="task-text" class="col-xs-12 form-control" placeholder="Breve descripci&oacute;n"></textarea></div>' +
                '<div class="form-group col-xs-12"><i class="fa fa-clock-o" aria-hidden="true"></i><label for="task-alert-date" class="hidden"></label><input id="task-alert-date" type="text" class="col-xs-5 form-control task-alert-time-selector" placeholder="fecha"><label for="task-alert-time" class="hidden"></label><input id="task-alert-time" type="text" class="col-xs-5 form-control task-alert-time-selector" placeholder="hora"></div>' +
                '<div class="form-group col-xs-12"><div id="dropzone-title" class="col-xs-12">Archivos adjuntos</div><form id="new-task-dropzone" action="?r=task/temp-store-file" class="dropzone"></form></div>' +
                '</div>';
            var modal = alertify.confirm().setting({
                'title': "Spy | Nueva tarea",
                'message': form,
                'default': "",
                'labels': {
                    ok: "AÑADIR TAREA",
                    cancel: "DESECHAR"
                },
                'movable': false,
                'modal': true,
                'closable': false,
                'transition': 'zoom',
                'onok': function (evt, value) {
                    var formItems = $('#modal-new-task-form'),
                        client = $('#task-client'),
                        type = $('#task-type'),
                        typeSelected = $('#task-type-selected'),
                        subject = $('#task-subject'),
                        text = $('#task-text'),
                        alertDate = $('#task-alert-date'),
                        alertTime = $('#task-alert-time');

                    function validateForm() {
                        var cnt = 0,
                            _validate = SPY.prototype.validation;
                        formItems.find('input').each(function (idx, input) {
                            if (_validate.isEmpty($(input))) {
                                $(input).css('border-Color', '#471818');
                                cnt++;
                            } else {
                                $(input).css('border-Color', 'rgb(0, 192, 23)');
                            }

                        });
                        if (_validate.isEmpty(text)) {
                            text.css('border-Color', '#471818');
                            cnt++
                        } else {
                            text.css('border-Color', 'rgb(0, 192, 23)');
                        }
                        if (client.val() == null) {
                            client.css('border-Color', '#471818');
                            cnt++
                        } else {
                            client.css('border-Color', 'rgb(0, 192, 23)');
                        }
                        if (typeSelected.attr('key') == undefined) {
                            typeSelected.css('border-width', '0 2px');
                            cnt++;
                        } else {
                            typeSelected.css('border-width', '0');
                        }
                        return cnt == 0;
                    }

                    if (validateForm()) {
                        $.ajax({
                            method: "POST",
                            url: "?r=task/create-task",
                            async: false,
                            data: {
                                'client': client.val(),
                                'type': typeSelected.attr('key'),
                                'subject': subject.val(),
                                'description': text.val(),
                                'alert': alertDate.val() + " " + alertTime.val()
                            }
                        }).done(function (confirm) {
                            confirm == 1 ? location.reload() : alertify.error("No se ha podido conectar con el servidor");
                        });
                    }
                    return false;
                },
                'oncancel': function () {
                    $.ajax({
                        method: "POST",
                        url: "?r=task/discard-task"
                    }).done(function (confirm) {
                        return confirm == 1;
                    });
                    modal.destroy();
                }
            }).show();
            var client_selector = $('#task-client'),
                type_selector = $('#task-type').find('ul'),
                type_selected = $('#task-type-selected'),
                date_picker = $('#task-alert-date'),
                date_time = $('#task-alert-time'),
                dropzone = $('#new-task-dropzone');
            // populate form
            if (client_selector.find('option').length == 1) {
                $.ajax({
                    method: "POST",
                    url: "?r=task/populate-form",
                    async: false
                }).done(function (data) {
                    var clients = data.clients,
                        types = data.types;
                    clients.forEach(function (client) {
                        $('<option>', {
                            'value': client.client_id,
                            'text': client.name
                        }).appendTo('#task-client');
                    });
                    types.forEach(function (type) {
                        if (type.name == "llamada de cortesía mensual") {
                            type.name = "LL.C.M";
                        }
                        $('<li>', {
                            'class': 'task-type',
                            'value': type.taskT_id,
                            'style': "background:" + type.color,
                            'text': type.name
                        }).appendTo(type_selector);
                    });
                });
                // load date/time pickers
                date_picker.datepicker({
                    'format': 'yyyy-mm-dd',
                    'autoclose': true,
                    'weekStart': '1'
                });
                date_time.timepicker({
                    'showDuration': true,
                    'timeFormat': 'H:i',
                    'step': 30,
                    'scrollDefault': 'now',
                    'orientation': 'right'
                });
                dropzone.dropzone();
            }
            $('.task-type').click(function () {
                type_selected.text($(this).text());
                type_selected.css('backgroundColor', $(this).css('backgroundColor'));
                type_selected.attr('key', $(this).val());
            });
            fullDate != undefined ? date_picker.val(fullDate) : null;
            client != undefined ? client_selector.val(client) : null;
        },
        // add note
        newNote: function () {
            var form = '<div id="form-new-note">' +
                '<div id="new-note-short-description" class="col-xs-12"><label for="new-note-sh-desc" class="col-xs-3">Asunto</label><input id="new-note-sh-desc" class="col-xs-9 form-control" /></div>' +
                '<div id="new-note-description"><label for="new-note-description" class="hidden"></label><textarea id="new-note-description-text" class="form-control"></textarea></div>' +
                '<div id="note-datetime"><input class="col-xs-4 form-control" id="note-date" placeholder="D&iacute;a"/><input class="col-xs-4 col-xs-offset-1 form-control" id="note-time" placeholder="Hora"/></div>' +
                '</div>';
            alertify.confirm().setting({
                'title': "Spy | Nueva nota",
                'message': form,
                'default': "",
                'labels': {
                    ok: "GUARDAR NOTA",
                    cancel: "DESECHAR"
                },
                'movable': false,
                'modal': true,
                'closable': false,
                'transition': 'zoom',
                'onok': function (evt, value) {
                    if (validateForm()) {
                        $.ajax({
                            method: "POST",
                            url: "?r=task/new-note",
                            data: {
                                subject: $('#new-note-sh-desc').val(),
                                description: $('#new-note-description-text').val(),
                                date: $('#note-date').val() + " " + $('#note-time').val()
                            }
                        }).done(function (confirm) {
                            confirm == 1 ? location.reload() : alertify.error("No ha sido posible conectar con el servidor");
                        });
                    }
                    return false;
                }
            }).show();
            $('#note-date').datepicker({
                'format': 'yyyy-mm-dd',
                'autoclose': true,
                'weekStart': '1'
            });
            $('#note-time').timepicker({
                'showDuration': true,
                'timeFormat': 'H:i',
                'step': 30,
                'scrollDefault': 'now',
                'orientation': 'right'
            });
            function validateForm() {
                var cnt = 0,
                    form = $('#form-new-note'),
                    _validate = SPY.prototype.validation;
                form.find('.form-control').each(function (idx, input) {
                    if (_validate.isEmpty($(input))) {
                        $(input).css('borderColor', '#471818');
                        cnt++;
                    } else {
                        $(input).css('borderColor', '#01DF01');
                    }
                });
                return cnt == 0;
            }
        },
        // task viewer
        taskViewer: function (task) {
            // get data from server
            $.ajax({
                method: "POST",
                url: "?r=task/get-full-task",
                data: {
                    id: task.id
                }
            }).done(function (fullTask) {
                // render form and get html code
                renderContent(fullTask, function (content) {
                    // show modal
                    alertify.alert().setting({
                        'title': "Spy | Gestión de tareas",
                        'message': content,
                        'label': "CERRAR",
                        'movable': false,
                        'modal': true,
                        'closable': false,
                        'padding': false,
                        'transition': 'zoom',
                        'onok': function () {
                            var report = $('.ajs-ok').attr('report');
                            if (report == undefined || report == "false") {
                                return true;
                            } else {
                                $.ajax({
                                    method: "POST",
                                    url: "?r=task/finalize",
                                    data: {
                                        id: $('#task-viewer').attr('key'),
                                        report: $('#task-report').val()
                                    }
                                }).done(function (confirm) {
                                    confirm == 1 ? location.reload() : alerttify.error("No se ha podido contactar con el servidor");
                                });
                                return false;
                            }
                        }
                    }).show();
                    // load tooltips
                    $('.file-item').tooltip();
                    // update description
                    $('#task-update-modal').click(function () {
                        var text = $('#task-description'),
                            btn = $(this);
                        $.ajax({
                            method: "POST",
                            url: "?r=task/update-desc",
                            data: {
                                id: $('#task-viewer').attr('key'),
                                text: text.val()
                            }
                        }).done(function (confirm) {
                            if (confirm == 1) {
                                text.css('backgroundColor', 'lightgrey');
                                btn.addClass('disabled');
                            }
                        });
                    });
                    // toggle files view
                    $('#task-files-modal').click(function () {
                        toggleView('files');
                    });
                    // toggle upload view
                    $('#task-upload-modal').click(function () {
                        toggleView('upload');
                    });
                    // toggle report view
                    $('#task-report-modal').click(function () {
                        toggleView('report');
                    });
                    // toggle description view
                    $('#task-description').keyup(function () {
                        var currentColor = $(this).css('backgroundColor'),
                            btn = $('#task-update-modal');
                        currentColor != "white" ? $(this).css('backgroundColor', 'white') : nulll;
                        btn.hasClass('disabled') ? btn.removeClass('disabled') : null;
                    });
                    // toggle report view
                    var taskReport = $('#task-report');
                    taskReport.click(function () {
                        if (taskReport.val() == "Escribe un reporte si es necesario. (OPCIONAL)") {
                            taskReport.val("");
                        }
                    });
                    taskReport.keyup(function () {
                        if (taskReport.val() == "Escribe un reporte si es necesario. (OPCIONAL)") {
                            taskReport.val("");
                        }
                    });
                    // load dropzone
                    $('#upload-file').dropzone();
                    // tooltip contact data
                    $('#task-client').tooltip({
                        html: true
                    });
                });
            });
            // function toggle views
            function toggleView(view) {
                var defaultView = $('#task-description'),
                    main = !defaultView.hasClass('hidden'),
                    modalBtn = $('.ajs-ok'),
                    newView;
                switch (view) {
                    case 'files':
                        newView = {
                            btn: $('#task-files-modal'),
                            view: $('#file-list')
                        };
                        break;
                    case 'upload':
                        newView = {
                            btn: $('#task-upload-modal'),
                            view: $('#upload-file')
                        };
                        break;
                    case 'report':
                        newView = {
                            btn: $('#task-report-modal'),
                            view: $('#task-report')
                        };
                        break;
                }
                if (!newView.view.hasClass('hidden')) {
                    newView.view.addClass('hidden');
                    defaultView.removeClass('hidden');
                } else {
                    if (main) {
                        defaultView.addClass('hidden');
                        newView.btn.css('border', '1px solid black');
                        newView.view.removeClass('hidden');
                    } else {
                        $('.optional-view').each(function (idx, item) {
                            $(item).addClass('hidden');
                        });
                        $('#task-action').find('.fa').each(function (idx, item) {
                            $(item).parent().css('border', '1px solid transparent');
                        });
                        newView.btn.css('border', '1px solid black');
                        newView.view.removeClass('hidden');
                    }
                }
                if (view == 'report') {
                    modalBtn.text('FINALIZAR').attr('report', true);
                    newView.view.hasClass('hidden') ? modalBtn.text('CERRAR').attr('report', false) : null;
                } else {
                    modalBtn.text() != "CERRAR" ? modalBtn.text('CERRAR').attr('report', false) : null;
                }
            }

            /* rendering task */
            function renderContent(fullTask, rendered) {
                var taskId = fullTask.id;
                var client = fullTask.client;
                var subject = fullTask.subject;
                var description = fullTask.description;
                var alert = fullTask.alert;
                var alertA = alert.split(' ');
                var dataA = alertA[0].split('-');
                var formattedDate = dataA[2] + "-" + dataA[1] + "-" + dataA[0];
                var timeA = alertA[1].split(':');
                var formattedTime = timeA[0] + ":" + timeA[1];
                var type = fullTask.type;
                var typeColor = fullTask.typeColor;
                var files = fullTask.files;
                var content = '<div id="task-viewer" key="' + taskId + '" class="container-fluid">';
                content += '<div id="task-header">';
                type == "llamada de cortesía mensual" ? type = "LL.C.M" : null;
                type == "servicio técnico" ? type = "S. Técnico" : null;
                content += '<div id="task-type" style="background:' + typeColor + '">' + type + '</div>';
                content += '<div id="task-client" data-toggle="tooltip" data-placement="top" title="<div>'+fullTask.contact+'<br />'+fullTask.phone+'<br />'+fullTask.mail+'</div>">' + client + '</div>';
                content += '<div id="task-time">';
                content += '<div class="time">' + formattedTime + '</div>';
                content += '<div class="date">' + formattedDate + '</div>';
                content += '</div>';
                content += '<div id="task-subject">' + subject + '</div>';
                content += '</div>';
                content += '<div id="task-body">';
                /* textarea editable default */
                content += '<textarea id="task-description" class="form-control">' + description + '</textarea>';
                /* file list */
                content += '<div id="file-list" class="hidden optional-view">';
                if (files.length == 0) {
                    content += '<div id="not-files">Sin archivos adjuntos.</div>';
                } else {
                    files.forEach(function (file) {
                        var ext = file.short_description.split('.')[1],
                            icon;
                        switch (ext) {
                            case "pdf":
                                icon = ["fa-file-pdf-o", "\f1c1"];
                                break;
                            case "txt":
                                icon = ["fa-file-text-o", "\f0f6"];
                                break;
                            case "jpeg":
                            case "jpg":
                            case "png":
                            case "ico":
                                icon = ["fa-file-image-o", "\f1c5"];
                                break;
                            case "doc":
                            case "docx":
                            case "odt":
                                icon = ["fa-file-word-o", "\f1c2"];
                                break;
                            default:
                                icon = ["fa-file-o", "\f016"];
                                break;
                        }
                        content += '<a href="?r=task/get-file&id=' + file.file_id + '">';
                        content += '<div class="file-item" data-toggle="tooltip" data-placement="bottom" title="' + file.short_description + '">';
                        content += '<i class="fa ' + icon[0] + '" aria-hidden="true" style="content:' + icon[1] + '"></i>';
                        content += '</div>';
                        content += '</a>'
                    });
                }
                content += '</div>';
                /* dropzone */
                content += '<form id="upload-file" action="?r=task/file-upload&id=' + taskId + '" class="dropzone hidden optional-view"></form>';
                /* report */
                content += '<textarea id="task-report" class="form-control hidden optional-view">Escribe un reporte si es necesario. (OPCIONAL)</textarea>';
                content += '</div>';
                content += '<div id="task-action">';
                content += '<div id="task-update-modal" class="btn btn-default disabled" data-toggle="tooltip" data-placement="right" title="Guardar cambios"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></div>';
                content += '<div id="task-files-modal" class="btn btn-default" data-toggle="tooltip" data-placement="right" title="' + files.length + ' archivos"><i class="fa fa-files-o" aria-hidden="true"></i></div>';
                content += '<div id="task-upload-modal" class="btn btn-default" data-toggle="tooltip" data-placement="right" title="Subir archivos"><i class="glyphicon glyphicon-plus" aria-expanded="true"></i></div>';
                content += '<div id="task-report-modal" class="btn btn-default" data-toggle="tooltip" data-placement="right" title="Finalizar tarea"><i class="fa fa-arrow-circle-o-right" aria-hidden="true"></i></div>';
                content += '</div>';
                content += '</div>';
                return rendered(content);
            }
        },
        // note viewer
        noteViewer: function (note) {
            var datetimeA = note.alert.split(' '),
                dateA = datetimeA[0].split('-'),
                formattedDate = dateA[2] + '-' + dateA[1] + "-" + dateA[0],
                formattedTIme = datetimeA[1];
            var content = '<div id="note-viewer" key="' + note.id + '" shared="' + note.shared + '"><div id="note-viewer-date">' + formattedDate + ' ' + formattedTIme + '</div><div id="note-viewer-text">' + note.text + '</div></div>';
            // show modal
            alertify.confirm().setting({
                'title': note.title,
                'message': content,
                'labels': {
                    'ok': "DEJAR DE MOSTRAR",
                    'cancel': "CERRAR"
                },
                'movable': false,
                'modal': true,
                'closable': false,
                'padding': false,
                'transition': 'zoom',
                'onok': function () {
                    var viewer = $('#note-viewer');
                    if (viewer.attr('shared') == 0) {
                        $.ajax({
                            method: "POST",
                            url: "?r=task/finalize-note",
                            data: {
                                id: viewer.attr('key')
                            }
                        }).done(function (confirm) {
                            confirm == 1 ? location.reload() : alertify.error("No se ha podido contactar con el servidor");
                        });
                    } else {
                        alertify.error("Esta nota pertenece al administrador. No tiene privilegios para ocultarla.");
                        return false;
                    }
                }
            }).show();
        },
        // add new contact
        newContact: function () {
            var _validate = SPY.prototype.validation;
            var contactForm = '<div id="contact-form" class="col-xs-12">' +
                '<div id="contact-error" class="alert-danger hidden"></div>' +
                '<div class="form-group"><label for="contact-name" class="col-xs-3">Nombre</label><input id="contact-name" class="form-control" placeholder="persona de contacto" /></div>' +
                '<div class="form-group"><label for="contact-phone" class="col-xs-3">Tel&eacute;fono</label><input id="contact-phone" class="form-control" maxlength="12" placeholder="tel&eacute;fono de contacto" /></div>' +
                '<div class="form-group"><label for="contact-mail" class="col-xs-3">@</label><input id="contact-mail" class="form-control" placeholder="mail de contacto" /></div>' +
                '<div class="form-group"><label for="contact-position" class="col-xs-3">Cargo:</label><select id="contact-position" class="form-control"><option disabled="disabled" selected="selected">Selecciona el cargo</option></select></select><div id="add-position" class="btn btn-default"><i class="fa fa-plus" aria-hidden="true"></i></div></div>' +
                '</div>';
            // render contact form
            var contactModal = alertify.confirm().setting({
                'title': "Spy | Gestión de contactos",
                'message': contactForm,
                'default': "",
                'labels': {
                    ok: "A&ntilde;adir contacto",
                    cancel: "Cancelar"
                },
                'movable': false,
                'modal': true,
                'closable': false,
                'transition': 'zoom',
                'onok': function (evt, value) {
                    var client_id = $('#client-header'),
                        contact_name = $('#contact-name'),
                        contact_phone = $('#contact-phone'),
                        contact_mail = $('#contact-mail'),
                        contact_position = $('#contact-position'),
                        error = $('#contact-error'),
                        cnt = 0,
                        message;
                    !error.hasClass('hidden') ? error.addClass('hidden') : null;
                    if (!_validate.isEmpty(contact_name)) {
                        contact_name.css('border-Color', 'rgb(0, 192, 23)');
                    } else {
                        contact_name.css('border-Color', '#471818');
                        cnt++;
                    }
                    if (!_validate.isEmpty(contact_phone) && _validate.isValidPhone(contact_phone)) {
                        contact_phone.css('border-Color', 'rgb(0, 192, 23)');
                    }
                    else {
                        contact_phone.css('border-Color', '#471818');
                        cnt++;
                    }
                    if (cnt == 0) {
                        $.ajax({
                            method: "POST",
                            url: "?r=client/create-contact",
                            async: false,
                            data: {
                                client: client_id.attr('key'),
                                name: contact_name.val(),
                                phone: contact_phone.val(),
                                mail: contact_mail.val(),
                                position: contact_position.val()
                            }
                        }).done(function (data) {
                            if (typeof data == "object") {
                                data.owner
                                    ? message = "Ya tienes un contacto con el n\u00famero <b>" + data.phone + "</b> asignado a: <b><span style='font-variant: all-petite-caps'>" + data.client + "</b></span>."
                                    : message = "<span style='font-variant: all-petite-caps'>" + data.commercial + "</span> ya tiene el n\u00famero <b>" + data.phone + "</b> asociado al cliente <b><span style='font-variant: all-petite-caps'>" + data.client + "</b></span>.";
                                error.html(message).removeClass('hidden');
                            } else {
                                if (data == 1) {
                                    setTimeout(function () {
                                        location.reload();
                                    }, 1000);
                                    return true;
                                }
                            }
                        });
                        return false;
                    }
                    else {
                        return false;
                    }
                }
            }).show();
            // load positions on contact form
            $.ajax({
                method: "POST",
                url: "?r=client/get-contact-positions",
                async: false
            }).done(function (positions) {
                positions.forEach(function (position) {
                    $('<option>', {
                        value: position.position_id,
                        text: position.name
                    }).appendTo($('#contact-position'));
                });
            });

            // add position action
            $('#add-position').click(function () {
                // destroy contact form
                contactModal.destroy();
                // open positions form and finalize (successfully or not) reopen contact form
                var positionModal = alertify.prompt().setting({
                    'title': "Spy | Gestión de cargos",
                    'message': "Introduce el nombre del nuevo cargo",
                    'default': "",
                    'labels': {
                        ok: "A&ntilde;adir cargo",
                        cancel: "Cancelar"
                    },
                    'movable': false,
                    'modal': true,
                    'closable': false,
                    'transition': 'zoom',
                    'onok': function (evt, value) {
                        if (value.length > 0) {
                            $.ajax({
                                method: "POST",
                                url: "?r=client/add-position",
                                async: false,
                                data: {
                                    position: value
                                }
                            }).done(function (data) {
                                data != 1 ? alertify.error("Ya existe el cargo " + data) : null;
                                positionModal.destroy();
                                setTimeout(function () {
                                    SPY.prototype.forms.newContact();
                                }, 750);
                            });
                            return false;
                        }
                        else {
                            alertify.error("Escribe el nombre del nuevo cargo");
                            return false;
                        }
                    },
                    'oncancel': function () {
                        positionModal.destroy();
                        setTimeout(function () {
                            SPY.prototype.forms.newContact();
                        }, 750);
                    }
                }).show();
            });
        },
        // new commercial
        newCommercial: function () {
            var form = '<div id="modal-new-commercial">' +
                '<div class="form-group col-xs-12"><label for="commercial-name">Nombre</label><input id="commercial-name" class="form-control" /></div>' +
                '<div class="form-group col-xs-12"><label for="commercial-lastName">Apellido</label><input id="commercial-lastName" class="form-control" /></div>' +
                '<div class="form-group col-xs-12"><label for="commercial-mail">Email</label><input id="commercial-mail" class="form-control" /></div>' +
                '<div class="form-group col-xs-12"><label for="commercial-pwd">Contrase&ntilde;a</label><input id="commercial-pwd" type="password" class="form-control" /></div>' +
                '<div class="form-group col-xs-12"><label for="commercial-re-pwd">Repite la contrase&ntilde;a</label><input id="commercial-re-pwd" type="password" class="form-control" /></div>' +
                '<div id="commercial-error" class="col-xs-12 hidden"></div>' +
                '</div>';
            // show modal
            var modal = alertify.confirm().setting({
                'title': "Spy | Nuevo comercial",
                'message': form,
                'default': "",
                'labels': {
                    ok: "AÑADIR COMERCIAL",
                    cancel: "CERRAR"
                },
                'movable': false,
                'modal': true,
                'closable': false,
                'transition': 'zoom',
                'onok': function (evt, value) {
                    var formItems = $('#modal-new-commercial'),
                        name = $('#commercial-name'),
                        lastName = $('#commercial-lastName'),
                        mail = $('#commercial-mail'),
                        pwd = $('#commercial-pwd'),
                        pwdR = $('#commercial-re-pwd'),
                        error = $('#commercial-error'),
                        _validate = SPY.prototype.validation;

                    function validateForm() {
                        var cnt = 0;
                        !error.hasClass('hidden') ? error.addClass('hidden') : null;
                        formItems.find('input').each(function (idx, input) {
                            if (_validate.isEmpty($(input))) {
                                $(input).css('border-Color', '#471818');
                                cnt++;
                            } else {
                                $(input).css('border-Color', 'rgb(0, 192, 23)');
                            }
                        });
                        if (cnt != 0) {
                            error.html('Todos los campos son obligatorios').removeClass('hidden');
                            return false;
                        } else {
                            if (!_validate.isValidMail(mail)) {
                                mail.css('border-Color', '#471818');
                                error.html('El formato del email no es correcto').removeClass('hidden');
                                return false;
                            }
                            if (pwd.val() != pwdR.val()) {
                                pwd.css('border-Color', '#471818');
                                pwdR.css('border-Color', '#471818');
                                error.html('Las contraseñas no coinciden').removeClass('hidden');
                                return false;
                            }
                            return true;
                        }
                    }

                    if (validateForm()) {
                        $.ajax({
                            method: "POST",
                            url: "?r=admin/create-commercial",
                            async: false,
                            data: {
                                'name': name.val(),
                                'lastName': lastName.val(),
                                'email': mail.val(),
                                'password': pwd.val()
                            }
                        }).done(function (confirm) {
                            confirm == 1 ? location.reload() : alertify.error("No se ha podido contactar con el servidor");
                        });
                    }
                    return false;
                }
            }).show();
        },
        // new shared note
        newSharedNote: function () {
            var form = '<div id="form-new-shared-note">' +
                '<div id="new-note-short-description" class="col-xs-12"><label for="new-note-sh-desc" class="col-xs-3">Asunto</label><input id="new-note-sh-desc" class="col-xs-9 form-control" /></div>' +
                '<div id="new-note-description"><label for="new-note-description" class="hidden"></label><textarea id="new-note-description-text" class="form-control"></textarea></div>' +
                '<div id="note-datetime"><input class="col-xs-4 form-control" id="note-date" placeholder="D&iacute;a"/><input class="col-xs-4 col-xs-offset-1 form-control" id="note-time" placeholder="Hora"/></div>' +
                '</div>';
            alertify.confirm().setting({
                'title': "Spy | Nueva nota",
                'message': form,
                'default': "",
                'labels': {
                    ok: "GUARDAR NOTA",
                    cancel: "DESECHAR"
                },
                'movable': false,
                'modal': true,
                'closable': false,
                'transition': 'zoom',
                'onok': function (evt, value) {
                    if (validateForm()) {
                        $.ajax({
                            method: "POST",
                            url: "?r=task/new-note",
                            data: {
                                subject: $('#new-note-sh-desc').val(),
                                description: $('#new-note-description-text').val(),
                                date: $('#note-date').val() + " " + $('#note-time').val()
                            }
                        }).done(function (confirm) {
                            confirm == 1 ? location.reload() : alertify.error("No ha sido posible conectar con el servidor");
                        });
                    }
                    return false;
                }
            }).show();
            $('#note-date').datepicker({
                'format': 'yyyy-mm-dd',
                'autoclose': true,
                'weekStart': '1'
            });
            $('#note-time').timepicker({
                'showDuration': true,
                'timeFormat': 'H:i',
                'step': 30,
                'scrollDefault': 'now',
                'orientation': 'right'
            });
            function validateForm() {
                var cnt = 0,
                    form = $('#form-new-shared-note'),
                    _validate = SPY.prototype.validation;
                form.find('.form-control').each(function (idx, input) {
                    if (_validate.isEmpty($(input))) {
                        $(input).css('borderColor', '#471818');
                        cnt++;
                    } else {
                        $(input).css('borderColor', '#01DF01');
                    }
                });
                return cnt == 0;
            }
        },
        // new guide
        newGuide: function () {
            var content = '<div id="guide-form" class="col-xs-12" valid="false">' +
                '<div class="col-xs-4 short-desc-title">Breve descripci&oacute;n</div>' +
                '<input id="short_description" class="form-control col-xs-8" />' +
                '<textarea id="guide-text" class="col-xs-12 form-control"></textarea>' +
                '<form class="col-xs-12 new-guide-dropzone dropzone" action="?r=admin/temp-store-file"></form>' +
                '</div>';
            alertify.confirm().setting({
                'title': "Spy | Gestión de guiones",
                'message': content,
                'default': "",
                'labels': {
                    'ok': "GUARDAR",
                    'cancel': "DESECHAR"
                },
                'movable': false,
                'modal': true,
                'closable': false,
                'transition': 'zoom',
                'onok': function () {
                    var form = $('#guide-form'),
                        short_desc = $('#short_description'),
                        text = $('#guide-text'),
                        _validate = SPY.prototype.validation;
                    if (form.attr('valid') == "true") {
                        if (validateForm()) {
                            $.ajax({
                                method: "POST",
                                url: "?r=admin/create-guide",
                                data: {
                                    short_d: short_desc.val(),
                                    text: text.val()
                                }
                            }).done(function (confirm) {
                                confirm == 1 ? location.reload() : alertify.error("No se ha podido contactar con el servidor");
                            })
                        } else {
                            form.attr('valid', 'false');
                        }
                    }
                    function validateForm() {
                        var cnt = 0;
                        if (_validate.isEmpty(short_desc)) {
                            short_desc.css('border-color', '#471818');
                            cnt++;
                        } else {
                            short_desc.css('border-color', 'rgb(0, 192, 23)')
                        }
                        if (_validate.isEmpty(text)) {
                            text.css('border-color', '#471818');
                            cnt++;
                        } else {
                            text.css('border-color', 'rgb(0, 192, 23)')
                        }
                        return cnt == 0;
                    }

                    return false;
                },
                'oncancel': function () {
                    $.ajax({
                        method: "POST",
                        url: "?r=admin/discard-guide"
                    }).done(function (confirm) {
                        return confirm == 1;
                    });
                }
            }).show();
            $('.ajs-ok').click(function () {
                $('#guide-form').attr('valid', "true");
            });
            $('.new-guide-dropzone').dropzone();
        }
    },
    effects: {
        // load main menu
        mainMenu: function () {
            /* vertical menu */
            var Accordion = function (el, multiple) {
                this.el = el || {};
                this.multiple = multiple || false;
                var links = this.el.find('.link');
                links.on('click', {el: this.el, multiple: this.multiple}, this.dropdown)
            };
            Accordion.prototype.dropdown = function (e) {
                var ele = e.data,
                    item = $(this),
                    next = item.next();
                next.slideToggle();
                item.parent().toggleClass('open');
                if (!ele.multiple) {
                    ele.el.find('.submenu').not(next).slideUp().parent().removeClass('open');
                }
            };
            var accordion = new Accordion($('#accordion'), false);
        },
        // animate tasks block on actions click
        animateTaskData: function (block, action) {
            if (action) {
                block.animate({
                    opacity: 0,
                    width: "0"
                }, 500);
            } else {
                block.animate({
                    opacity: 1,
                    width: "+=75%"
                }, 500);
            }
        },
        animateBlock: function (block, action) {
            if (action) {
                block.addClass('closed hidden');
            } else {
                setTimeout(function () {
                    block.removeClass('closed hidden');
                }, 500);
            }
        },
        // enable description
        enableDescriptionUpdate: function (textarea) {
            var submit = textarea.parent().parent().find('.task-action-update');
            textarea.css('backgroundColor', 'white');
            submit.hasClass('disabled') ? submit.removeClass('disabled') : null;
        },
        // clear report text
        clearReportText: function (textarea) {
            textarea.text() == "Escribe un reporte si es necesario. (OPCIONAL)" ? textarea.text("") : null;
        },
        // expand file add
        expandFileUpload: function (element) {
            var task = element.parent().parent(),
                viewer = task.find('.not-files'),
                uploadZone = task.find('.task-dropzone');
            !viewer.length > 0 ? viewer = task.find('.file-list') : null;
            if (!uploadZone.hasClass('hidden')) {
                uploadZone.addClass('hidden');
                viewer.show();
            } else {
                viewer.hide();
                uploadZone.removeClass('hidden');
            }
        },
        // enable faculty mode
        enableFacultyMode: function () {
            var state = $('#faculty-switch-input').is(':checked'),
                filters = $('#faculty-mode'),
                fields = $('#extend-location').find('.form-control'),
                codpost = $('#find-postal-code'),
                validate = $('#find-address');
            if (!state) {
                filters.removeClass('hidden');
                codpost.attr('disabled', true).css('opacity', '0.50');
                fields.attr('disabled', true).css('opacity', '0.50');
                validate.attr('disabled', true).css('opacity', '0.50');
            } else {
                filters.addClass('hidden');
                filters.find('select').each(function (idx, item) {
                    $(item).get(0).selectedIndex = 0;
                });
                codpost.attr('disabled', false).css('opacity', '1');
                fields.attr('disabled', false).css('opacity', '1');
                validate.attr('disabled', false).css('opacity', '1');
            }
        },
        // toggle calendar type´s visibility and re-render calendar events
        toggleCalendarType: function (type) {
            var calendar = $('#calendar-main'),
                tColor,
                active,
                cColor,
                stateColor,
                filters = SPY.commons.filters;
            stateColor = type.find('.calendar-toggle-type');
            active = stateColor.css('backgroundColor') == 'rgb(0, 255, 30)';
            cColor = stateColor.parent().css('backgroundColor');
            if (active) {
                filters.push(cColor);
                stateColor.css('backgroundColor', 'rgb(255, 0, 0)');
                calendar.fullCalendar('rerenderEvents');
            }
            else {
                tColor = filters.indexOf(cColor);
                tColor > -1 ? filters.splice(tColor, 1) : null;
                stateColor.css('backgroundColor', 'rgb(0, 255, 30)');
                calendar.fullCalendar('rerenderEvents');
            }
        },
        // toggle task view
        toggleTaskView: function (element) {
            var selected = element.attr('key'),
                item = element.parent().parent(),
                defaultView = item.find('.task-list-description'),
                main = !defaultView.hasClass('hidden'),
                newView;
            switch (selected) {
                case 'files':
                    newView = {
                        btn: item.find('.action-files'),
                        view: item.find('.task-list-files')
                    };
                    break;
                case 'upload':
                    newView = {
                        btn: item.find('.action-upload'),
                        view: item.find('.task-list-upload')
                    };
                    break;
                case 'report':
                    newView = {
                        btn: item.find('.action-report'),
                        view: item.find('.task-list-report')
                    };
                    break;
                default:
                    newView = {
                        btn: item.find('.action-home'),
                        view: defaultView
                    };
                    break;
            }

            if (defaultView === newView.view) {
                item.find('.optional-task-view').each(function (idx, item) {
                    $(item).addClass('hidden');
                });
                item.find('.task-list-expanded-actions').find('.btn').each(function (idx, item) {
                    $(item).css('border', '1px solid #ccc');
                });
                defaultView.removeClass('hidden');
            } else {
                if (!newView.view.hasClass('hidden')) {
                    newView.view.addClass('hidden');
                    newView.btn.css('border', '1px solid #ccc');
                    defaultView.removeClass('hidden');
                } else {
                    if (main) {
                        defaultView.addClass('hidden');
                        newView.btn.css('border', '1px solid black');
                        newView.view.removeClass('hidden');
                    } else {
                        item.find('.optional-task-view').each(function (idx, item) {
                            $(item).addClass('hidden');
                        });
                        item.find('.task-list-expanded-actions').find('.btn').each(function (idx, item) {
                            $(item).css('border', '1px solid #ccc');
                        });
                        newView.btn.css('border', '1px solid black');
                        newView.view.removeClass('hidden');
                    }
                }
            }
        },
        // load home clock
        loadClock: function () {
            var monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
            var dayNames = ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"];
            var newDate = new Date();
            newDate.setDate(newDate.getDate());
            $('#Date').html(dayNames[newDate.getDay()] + " " + newDate.getDate() + ' ' + monthNames[newDate.getMonth()] + ' ' + newDate.getFullYear());
            setInterval(function () {
                var seconds = new Date().getSeconds();
                $("#sec").html(( seconds < 10 ? "0" : "" ) + seconds);
            }, 1000);
            setInterval(function () {
                var minutes = new Date().getMinutes();
                $("#min").html(( minutes < 10 ? "0" : "" ) + minutes);
            }, 1000);

            setInterval(function () {
                var hours = new Date().getHours();
                $("#hours").html(( hours < 10 ? "0" : "" ) + hours);
            }, 1000);

        },
        // slide admin tasks
        slideAdminTask: function (btn) {
            var _line = btn.parent().parent(),
                _list = _line.parent(),
                _info = _line.find('.task-info');
            if (!_info.is(':visible')) {
                _list.find('.task-line').not(_info).each(function (idx, info) {
                    var inf = $(info).find('.task-info');
                    inf.is(':visible') ? inf.slideUp() : null;
                });
                _info.slideDown();
            } else {
                _info.slideUp();
            }
        },
        // slide up and down guide
        slideGuide: function (guide) {
            var _line = guide.parent(),
                _list = _line.parent(),
                _info = _line.find('.guide-info');
            if (!_info.is(':visible')) {
                _list.find('.guide-item').not(_line).each(function (idx, info) {
                    var inf = $(info).find('.guide-info');
                    inf.is(':visible') ? inf.slideUp() : null;
                });
                _info.slideDown();
            } else {
                _info.slideUp();
            }
        },
        // enable guide update
        enableGuideUpdate: function (textarea) {
            var submit = textarea.parent().parent().find('.guide-save');
            textarea.css('backgroundColor', 'white');
            submit.hasClass('disabled') ? submit.removeClass('disabled') : null;
        },
        // enable note update
        enableUpdateNote: function(textarea){
            var submit = textarea.parent().parent().find('.note-update');
            textarea.css('backgroundColor', 'white');
            submit.hasClass('disabled') ? submit.removeClass('disabled') : null;
        }
    },
    actions: {
        // add/delete client to route
        clientRoute: function (btn) {
            var item = btn.parent().parent().parent(),
                details = item.find('.client-details'),
                coordinates = details.find('.expand-map').attr('coords'),
                client = item.attr('key'),
                url,
                t,
                text;

            if (!coordinates) {
                alertify.error('No hay datos de geolocalizaci\u00f3n para: <strong>' + item.find('.client-name').text() + "</strong>");
            } else {
                if (!btn.hasClass('added')) {
                    url = "?r=client/add-to-route";
                    text = " <strong>a\u00f1adido</strong> a tu ruta";
                    t = true;
                }
                else {
                    url = "?r=client/delete-of-route";
                    text = " <strong>eliminado</strong> de la ruta";
                    t = false;
                }
                $.ajax({
                    method: "POST",
                    url: url,
                    data: {
                        id: client
                    }
                }).done(function (confirm) {
                    if (confirm == 1) {
                        t ? btn.addClass('added') : btn.removeClass('added');
                        alertify.warning(item.find('.client-name').text() + text);
                    }
                });
            }
        },
        // expand client info
        expandClient: function (expander) {
            var item = expander.parent().parent().parent(),
                client = item.attr('key'),
                data = item.find('.client-details'),
                map = $('#map-' + client),
            /* close others */
                AllDetails = $('.client-item').find('.client-details');
            AllDetails.not(this).each(function (idx, obj) {
                $(obj).slideUp('slow', function () {
                    $(obj).addClass('detail-close').removeClass('detail-open');
                });
                $(obj).find('.contact_main').popover('hide');
            });
            /* open/close details */
            if (data.hasClass('detail-close')) {
                data.slideDown('slow', function () {
                    data.addClass('detail-open').removeClass('detail-close');
                });
                /* load expand map */
                setTimeout(function () {
                    item.find('.contact_main').popover('show');
                    map.length > 0 ? SPY.prototype.actions.loadMap(map) : null;
                }, 600);
            } else {
                item.find('.contact_main').popover('hide');
                map.first().gmap3('destroy');
                data.slideUp('slow', function () {
                    data.addClass('detail-close').removeClass('detail-open');
                });
            }
        },
        // load map on client/index on expand client
        loadMap: function (map) {
            var coordinate = map.attr('coords').split(','),
                coordinates = [coordinate[0], coordinate[1]],
                address = map.attr('address'),
                city = map.attr('city'),
                cp = map.attr('CP'),
                province = map.attr('province'),
                loading = '<img id="load-icon" src="./img/load-icon.gif" style="position: inherit;z-index: 9999999; margin: 20.5% 38.5%; height: 75px;" />',
                container = $('<div>', {
                    id: 'map-container'
                });
            map.append(container).append(loading);
            var view = map.first();
            setTimeout(function () {
                view.gmap3({
                    center: coordinates,
                    zoom: 17,
                    mapTypeId: google.maps.MapTypeId.HYBRID,
                    mapTypeControl: true,
                    addressControlOptions: {
                        position: 'LEFT_TOP'
                    },
                    mapTypeControlOptions: {
                        style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                    },
                    navigationControl: true,
                    scrollWheel: true,
                    streetViewControl: true,
                    fullScreenControl: true,
                    fullScreenControlOptions: {
                        position: 'RIGHT_TOP'
                    }
                }).marker({
                    position: coordinates
                }).infowindow({
                    position: coordinates,
                    content: '<div class="map-info-location"><div class="address">' + address + '</div><div class="extra">' + city + ", " + cp + ", " + province + '</div></div>',
                }).then(function (infowindow) {
                    var MAP = this.get(0);
                    var marker = this.get(1);
                    marker.addListener('click', function () {
                        infowindow.open(MAP, marker);
                    });
                });
                view.find('#load-icon').remove();
            }, 1500);
        },
        // switch task blocks
        switchTaskBLocks: function (block) {
            if (!block.hasClass('not-allowed')) {
                var _effect = SPY.prototype.effects,
                    type = block.attr('key'),
                    icon = block.children(),
                    container = block.parent().parent(),
                    dataB = container.find('.task-data'),
                    reportB = container.find('.task-report'),
                    filesB = container.find('.task-files'),
                    minimize = true,
                    maximize = false;

                if (type == "report") {
                    if (!filesB.hasClass('closed')) {
                        _effect.animateBlock(filesB, minimize);
                        _effect.animateBlock(reportB, maximize);
                    } else {
                        if (reportB.hasClass('closed')) {
                            _effect.animateTaskData(dataB, minimize);
                            _effect.animateBlock(reportB, maximize);
                            icon.removeClass('fa-arrow-circle-o-right').addClass('fa-arrow-circle-o-left');
                        } else {
                            _effect.animateBlock(reportB, minimize);
                            _effect.animateTaskData(dataB, maximize);
                            icon.removeClass('fa-arrow-circle-o-left').addClass('fa-arrow-circle-o-right');
                        }
                    }
                } else {
                    if (!reportB.hasClass('closed')) {
                        _effect.animateBlock(reportB, minimize);
                        _effect.animateBlock(filesB, maximize);
                    } else {
                        if (filesB.hasClass('closed')) {
                            _effect.animateTaskData(dataB, minimize);
                            _effect.animateBlock(filesB, maximize);
                        } else {
                            _effect.animateBlock(filesB, minimize);
                            _effect.animateTaskData(dataB, maximize);
                        }
                    }
                }
            }
        },
        // create contact
        setAsContactMain: function (contact) {
            var contactId = contact.attr('key');
            $.ajax({
                method: "POST",
                url: "?r=client/set-contact-main",
                data: {
                    contact: contactId
                }
            }).done(function (confirm) {
                confirm == 1 ? location.reload() : alertify.error("No se ha podido contactar con el servidor");
            });
        },
        // update task description
        updateTaskDescription: function (element) {
            if (!element.hasClass('not-allowed')) {
                var taskB = element.parent(),
                    key = taskB.attr('key'),
                    text = taskB.parent().find('.task-data-desc'),
                    data = text.val();
                $.ajax({
                    method: "POST",
                    url: "?r=task/update-desc",
                    data: {
                        id: key,
                        text: data
                    }
                }).done(function (confirm) {
                    if (confirm == 1) {
                        text.css('backgroundColor', 'lightgrey');
                        element.addClass('disabled');
                    }
                });
            }
        },
        // finalize task
        finalizeTask: function (element, reload) {
            var task = element.parent().parent(),
                id = task.find('.task-actions').attr('key'),
                report = task.find('.task-report-text'),
                text;
            report.val() == "Escribe un reporte si es necesario. (OPCIONAL)" ? report.val("") : null;
            $.ajax({
                method: "POST",
                url: "?r=task/finalize",
                data: {
                    id: id,
                    report: report.val()
                }
            }).done(function (confirm) {
                if (confirm == 1) {
                    if (reload == undefined) {
                        setTimeout(function () {
                            window.location.reload();
                        }, 750);
                    } else {
                        setTimeout(function () {
                            task.remove()
                        }, 750);
                    }
                }
            });
        },
        // populate client´s map
        populateClientMap: function (faculty, facultyData) {
            var formInputs,
                formObj,
                formattedAddress,
                LIST = true,
                facultyINFO = {
                    title: "Gesti\u00f3n de facultades",
                    name: "facultad",
                    message: "Escribe el nombre de la nueva facultad:",
                    confirm: "Nueva facultad a\u00f1adida: "
                };
            faculty ? formInputs = $('#faculty') : formInputs = $('#extend-location');
            faculty
                ? formObj = {
                'address': $('<input>', {value: facultyData.address}),
                'city': $('<input>', {value: "GRANADA"}),
                'province': $('<input>', {value: "GRANADA"}),
                'postalCode': $('<input>', {value: ""}),
                'coordinates': $('#faculty-coordinates')
            }
                : formObj = {
                'address': $('#spyclient-address'),
                'city': $('#spyclient-city'),
                'province': $('#spyclient-province'),
                'postalCode': $('#spyclient-postal_code'),
                'coordinates': $('#spyclient-coordinates'),
                'falseMap': $('#false-client-map'),
                'locationMap': $('#client-map')
            };
            if (validateForm()) {
                getStreets(formObj, function (formattedData) {
                    if (formattedData == false) {
                        alertify.confirm().setting({
                            'title': "Spy | Localizador ",
                            'message': 'No se han encontrado coincidencias. \n¿Quieres seleccionar la ubicación en un mapa?',
                            'default': "",
                            'labels': {
                                ok: "SÍ ",
                                cancel: "MÁS TARDE"
                            },
                            'movable': false,
                            'modal': true,
                            'closable': false,
                            'transition': 'zoom',
                            'onok': function (evt, value) {
                                if (faculty) {
                                    SPY.commons.loadIcon.removeClass('hidden');
                                    setTimeout(function () {
                                        SPY.commons.loadIcon.addClass('hidden');
                                        openMapDragger(faculty, formObj, facultyINFO, facultyData);
                                    }, 1500);
                                } else {
                                    SPY.commons.loadIcon.removeClass('hidden');
                                    setTimeout(function () {
                                        SPY.commons.loadIcon.addClass('hidden');
                                        openMapDragger(faculty, formObj, null, facultyData);
                                    }, 1500);
                                }
                            }
                        }).show();
                    } else {
                        if (formattedData.length == 1) {
                            formattedData[0].city != formObj.city.val() ? formattedData[0].partial = true : null;
                            if (formattedData[0].partial) {
                                if ("street" in formattedData[0]) {
                                    selectFineLocation(!LIST, formObj, formattedData, faculty, facultyData);
                                } else {
                                    var alert = alertify.confirm().setting({
                                        'title': "Spy | Localizador ",
                                        'message': '<span style="text-align: center;"><b>La busqueda no es precisa.</b></div>',
                                        'default': "",
                                        'labels': {
                                            ok: "VOLVER A ESCRIBIR LA DIRECCIÓN ",
                                            cancel: "UBICAR EN UN MAPA"
                                        },
                                        'movable': false,
                                        'modal': true,
                                        'closable': false,
                                        'transition': 'zoom',
                                        'onok': function (evt, value) {
                                            alert.destroy();
                                            if (faculty) {
                                                SPY.commons.loadIcon.removeClass('hidden');
                                                setTimeout(function () {
                                                    SPY.commons.loadIcon.addClass('hidden');
                                                    SPY.prototype.forms.newFaculty({
                                                        title: "Gesti\u00f3n de facultades",
                                                        name: "facultad",
                                                        message: "Escribe el nombre de la nueva facultad:",
                                                        confirm: "Nueva facultad a\u00f1adida: "
                                                    }, facultyData);
                                                }, 1500);
                                            }
                                        },
                                        'oncancel': function (evt, value) {
                                            faculty ? openMapDragger(faculty, formObj, facultyINFO, facultyData) : openMapDragger(faculty, formObj, null, facultyData);
                                        }
                                    }).show();
                                }
                            } else {
                                populateForm(formObj, formattedData[0]);
                            }
                        } else {
                            SPY.commons.loadIcon.removeClass('hidden');
                            setTimeout(function () {
                                SPY.commons.loadIcon.addClass('hidden');
                                selectFineLocation(LIST, formObj, formattedData, faculty, facultyData);
                            }, 1500);
                        }
                    }
                });
            }

            /* validate form */
            function validateForm() {
                var x, cnt = 0;
                formInputs.find('input').each(function (idx, item) {
                    !$(item).attr('valid') ? x = $(item).val().length : x = 1;
                    x == 0 ? $(item).css('border-Color', 'rgb(146, 50, 50)') : $(item).css('border-Color', 'forestgreen');
                    x == 0 ? cnt++ : null;
                });
                return cnt == 0;
            }

            /* get request to google geo position */
            function getStreets(locationObj, fn) {
                formattedAddress = locationObj.address.val() + ", " + locationObj.city.val() + ", " + locationObj.province.val() + ", " + locationObj.postalCode.val();
                $.ajax({
                    method: "GET",
                    url: "http://maps.googleapis.com/maps/api/geocode/json",
                    data: {
                        sensor: false,
                        address: formattedAddress,
                        components: "country:ES",
                        language: "ES"
                    }
                }).done(function (data) {
                    parseData(data, function (streets) {
                        return fn(streets);
                    });
                });

            }

            /* parse data of google geo location */
            function parseData(data, parsed) {
                var status = data.status,
                    list = data.results,
                    locationObj,
                    streetList;
                if (status == "OK") {
                    streetList = [];
                    var item = 0;
                    list.forEach(function (object) {
                        locationObj = parseLocationObj(object, false, function (dataParsed) {
                            streetList.push(dataParsed);
                        });
                        item++;
                    });
                    streetList.length = item;
                    return parsed(streetList);
                } else if (status == "ZERO_RESULTS") {
                    return false;
                } else {
                    alertify.error("No se ha podido contactar con el servidor");
                }
            }

            /* parse one object */
            function parseLocationObj(obj, piker, parsed) {
                var locationObj = {},
                    addressComponents;
                piker == undefined ? piker = false : null;
                !piker
                    ? locationObj.formattedAddress = obj.formatted_address
                    : locationObj.formattedAddress = obj.formattedAddress;
                !piker ? locationObj.partial = obj.partial_match : false;
                !piker
                    ? locationObj.formattedCoordinates = obj.geometry.location.lat + ", " + obj.geometry.location.lng
                    : locationObj.formattedCoordinates = obj.latitude + ", " + obj.longitude;
                !piker
                    ? addressComponents = obj.address_components
                    : addressComponents = [obj.addressComponents];
                if (!piker) {
                    addressComponents.forEach(function (object, idx) {
                        object.types[0] == "street_number" ? locationObj.number = object.long_name : null;
                        object.types[0] == "route" ? locationObj.street = object.long_name : null;
                        object.types[0] == "postal_code" ? locationObj.postalCode = object.long_name : null;
                        object.types[0] == "administrative_area_level_2" ? locationObj.province = object.long_name : null;
                        object.types[0] == "locality" ? locationObj.city = object.long_name : null;
                    });
                } else {
                    locationObj.number = obj.addressComponents.number;
                    locationObj.street = obj.addressComponents.streetName;
                    locationObj.postalCode = obj.addressComponents.postalCode;
                    locationObj.province = "GRANADA";
                    locationObj.city = obj.addressComponents.city;
                }
                return parsed(locationObj);
            }

            /* open dialog form confirm the fine street*/
            function selectFineLocation(list, formObj, data, faculty, facultyData) {
                var message,
                    streetList,
                    source,
                    ask,
                    labels,
                    locationObj,
                    error,
                    streets;
                if (!list) {
                    message = "Se ha encontrado un resultado parcial.";
                    ask = "¿Es correcta la ubicaci&oacute;n?";
                    streetList = '<div id="street-list" class="col-xs-12"><div class="col-xs-12 street-item">' + data[0].formattedAddress + '</div></div>';
                    labels = {
                        ok: "SÍ",
                        cancel: "NO"
                    };
                } else {
                    message = "Se han encontrado m&uacute;ltiples coincidencias.";
                    ask = "Selecciona la ubicaci&oacute;n correcta:";
                    streetList = '<div id="street-list" class="col-xs-12">';
                    data.forEach(function (item, idx) {
                        streetList += '<div key="' + idx + '"class="col-xs-12 street-item">' + item.formattedAddress + '</div>';
                    });
                    streetList += "</div>";
                    labels = {
                        ok: "CONFIRMAR",
                        cancel: "¿NO EST&Aacute; EN LA LISTA?"
                    };
                }
                source = '<div class="col-xs-12">';
                source += '<div id="fine-location-title" class="col-xs-12">' + message + '</div>';
                source += '<div id="fine-location-ask" class="col-xs-12">' + ask + '</div>';
                source += streetList;
                source += '</div>';

                alertify.confirm().setting({
                    'title': "Spy | Localizador",
                    'message': source,
                    'default': "",
                    'labels': labels,
                    'movable': false,
                    'modal': true,
                    'closable': false,
                    'transition': 'zoom',
                    'onok': function (evt, value) {
                        var btnOk = $('.ajs-ok'),
                            error = $('.street-list-error');
                        if (btnOk.attr('selected') == "selected") {
                            !list ? locationObj = data[0] : locationObj = data[btnOk.attr('key')];
                            !list ? streets.attr('selected', 'selected') : null;
                            error.length == 1 ? error.remove() : null;
                            if (faculty) {
                                SPY.commons.loadIcon.removeClass('hidden');
                                setTimeout(function () {
                                    SPY.commons.loadIcon.addClass('hidden');
                                    SPY.prototype.forms.newFaculty(facultyINFO, facultyData, locationObj);
                                }, 750);
                                return true;
                            } else {
                                populateForm(formObj, locationObj);
                                return true;
                            }
                        } else {
                            error.length == 1 ? error.remove() : null;
                            $('#street-list').prepend('<div class="col-xs-12 bg-danger street-list-error" style="text-align: center;width: 100%;padding: 3%;border-bottom: 7px solid rgba(0, 0, 0, 0.52);"><b>Selecciona una ubicaci&oacute;n</b></div>');
                            return false;
                        }
                    },
                    'oncancel': function () {
                        openMapDragger(faculty, formObj, facultyINFO, facultyData);
                    }
                }).show();
                streets = $('.street-item');
                streets.click(function () {
                    var selected = $(this),
                        error = $('.street-list-error');
                    error.length == 1 ? error.remove() : null;
                    streets.each(function (idx, item) {
                        $(item).css('background-Color', 'white');
                    });
                    selected.css('background-Color', 'rgba(51, 5, 5, 0.33)');
                    $('.ajs-ok').attr('selected', 'selected').attr('key', $(this).attr('key'));
                });
            }

            /* populate the form with location selected */
            function populateForm(form, location) {
                var container = '<div id="map-container"></div>',
                    loading = '<img id="load-icon" src="img/load-icon.gif" style="position: inherit;z-index: 9999999; margin: 20.5% 38.5%; height: 75px;" />',
                    coordinatesArray,
                    coordinatesObj,
                    view;
                location.number ? form.address.val(location.street + ", " + location.number) : form.address.val(location.street + ", s/n");
                form.city.val(location.city);
                form.province.val(location.province);
                form.postalCode.val(location.postalCode);
                form.coordinates.val(location.formattedCoordinates);
                form.coordinates.attr('valid', true);
                coordinatesArray = location.formattedCoordinates.split(',');
                coordinatesObj = [coordinatesArray[0], coordinatesArray[1]];
                form.falseMap.hasClass('hidden') ? form.locationMap.empty() : form.falseMap.addClass('hidden');
                form.locationMap.append(container).removeClass('hidden');
                view = $('#map-container');
                view.append(loading);
                setTimeout(function () {
                    view.gmap3({
                        center: coordinatesObj,
                        zoom: 30,
                        mapTypeId: google.maps.MapTypeId.HYBRID,
                        mapTypeControl: true,
                        addressControlOptions: {
                            position: 'LEFT_TOP'
                        },
                        mapTypeControlOptions: {
                            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU
                        },
                        navigationControl: true,
                        scrollWheel: true,
                        streetViewControl: true,
                        fullScreenControl: true,
                        fullScreenControlOptions: {
                            position: 'RIGHT_TOP'
                        }
                    }).marker({
                        position: coordinatesObj
                    });
                    view.css('position', 'inherit').find('#load-icon').remove();
                }, 1500);
            }

            /* if no one street is fine, open map dragger */
            function openMapDragger(faculty, formObj, facultyInfo, facultyData) {
                var source = '<div id="location-picker-main"><div class="col-xs-12"><b>Arrastra el marcador y selecciona la ubicaci&oacute;n.</b></div><input id="map-street-selected" class="col-xs-12 form-control" disabled="disabled" /><div id="location-picker" class="col-xs-12"></div></div>',
                    submit,
                    input,
                    locationObj = {};
                SPY.commons.loadIcon.removeClass('hidden');
                setTimeout(function () {
                    SPY.commons.loadIcon.addClass('hidden');
                    alertify.confirm().setting({
                        'title': "Spy | Localizador",
                        'message': source,
                        'default': "",
                        'labels': {
                            'ok': "CONFIRMAR",
                            'cancel': "CANCELAR"
                        },
                        'movable': false,
                        'modal': true,
                        'closable': false,
                        'transition': 'zoom',
                        'onok': function (evt, value) {
                            submit = $('.ajs-ok');
                            input = $('#map-street-selected');
                            if (submit.attr('selected') == "selected") {
                                if (!faculty) {
                                    populateForm(formObj, locationObj);
                                }
                                else {
                                    SPY.commons.loadIcon.removeClass('hidden');
                                    setTimeout(function () {
                                        SPY.commons.loadIcon.addClass('hidden');
                                        SPY.prototype.forms.newFaculty(facultyInfo, facultyData, locationObj);
                                    }, 750);
                                }
                                return true;
                            } else {
                                input.css('border-color', 'rgba(44, 8, 8, 0.47)');
                                return false;
                            }
                        },
                        'oncancel': function(evt, value){
                            return true;
                        }
                    }).show();
                    $('#location-picker').locationpicker({
                        location: {latitude: 37.15313133308989, longitude: -3.5919255670584107},
                        locationName: "",
                        zoom: 10,
                        radius: 0,
                        scrollwheel: true,
                        inputBinding: {locationNameInput: $('#map-street-selected')},
                        onchanged: function (currentLocation, radius, isMarkerDropped) {
                            var mapContext = $(this).locationpicker('map'),
                                input = $('#map-street-selected');
                            input.html(currentLocation.locationName);
                            mapContext.map.setZoom(16);
                            parseLocationObj(mapContext.location, true, function (parseData) {
                                locationObj = parseData;
                                $('.ajs-ok').attr('selected', true);
                            });
                        }
                    });
                }, 1250);
            }
        },
        // auto-complete Postal code
        autoCompletePostalCode: function () {
            var findPC = $('#find-postal-code'),
                address = $('#spyclient-address'),
                city = $('#spyclient-city'),
                province = $('#spyclient-province'),
                postalCode = $('#spyclient-postal_code'),
                coordinates = $('#spyclient-coordinates'),
                cp = postalCode.val(),
                pro, ct;
            if (cp.length == 5) {
                getDataOfPC(cp, function (data) {
                    if (data.status == "OK") {
                        var result = data.results[0];
                        if (!result.partial_match) {
                            var list = result.address_components;
                            list.forEach(function (item) {
                                item.types[0] == "locality" ? ct = item.long_name : null;
                                item.types[0] == "administrative_area_level_2" ? pro = item.long_name : null;
                            });
                            province.val(pro).attr('valid', true);
                            city.val(ct).attr('valid', true);
                            postalCode.tooltip('disable').css('border-color', '#3c763d').attr('valid', true);
                            findPC.find('i').removeClass('fa-search').addClass('fa-check');
                            setTimeout(function () {
                                $('.field-spyclient-postal_code').removeClass('has-error').addClass('has-success');
                            }, 500);
                            $('#spyclient-address').focus();
                        } else {
                            postalCode.tooltip('hide');
                            alertify.error('No se han encontrado coincidencias');
                            setTimeout(function () {
                                postalCode.html("");
                                postalCode.tooltip('show');
                            }, 1000);
                        }

                    }
                });
            } else {
                postalCode.tooltip('show');
            }
            /* get data of postalCode from google geo-location */
            function getDataOfPC(cod, fn) {
                $.ajax({
                    method: "GET",
                    url: "http://maps.googleapis.com/maps/api/geocode/json",
                    data: {
                        sensor: false,
                        address: cod,
                        components: "country:ES",
                        language: "ES"
                    }
                }).done(function (data) {
                    return fn(data);
                });
            }
        },
        // load full commercial data at selection
        loadCommercialTasks: function (commercial) {
            var calendar = $('#admin-calendar');
            $.ajax({
                method: "POST",
                url: "?r=admin/get-commercial-tasks",
                async: false,
                data: {
                    commercial: commercial
                }
            }).done(function (data) {
                if (data.events.length > 0) {
                    calendar.fullCalendar('removeEventSource', SPY.commons.calendarSource);
                    SPY.commons.calendarSource = data.events;
                    setTimeout(function () {
                        calendar.fullCalendar('addEventSource', SPY.commons.calendarSource);
                    }, 750);
                } else {
                    alertify.error("No hay tareas disponibles para este comercial");
                }

            });
        },
        loadCommercialFullData: function (commercial) {
            var _loadIcon = SPY.commons.loadIcon;
            _loadIcon.removeClass('hidden');
            resetView();
            getData(function (data) {
                /* load donuts */
                setTimeout(function () {
                    var stats,
                        colors;
                    data.today.types.length == 0 ? stats = [{
                        label: "Sin actividad",
                        value: 1,
                        formatted: "0 Tareas"
                    }] : stats = data.today.types;
                    data.today.types.length == 0 ? colors = ['#d46a6a'] : colors = data.today.colors;
                    Morris.Donut({
                        element: 'donut-today',
                        data: stats,
                        colors: colors,
                        resize: true,
                        formatter: function (x, data) {
                            return data.formatted;
                        }
                    });

                    data.week.types.length == 0 ? stats = [{
                        label: "Sin actividad",
                        value: 1,
                        formatted: "0 Tareas"
                    }] : stats = data.week.types;
                    data.week.types.length == 0 ? colors = ['#d46a6a'] : colors = data.week.colors;
                    Morris.Donut({
                        element: 'donut-week',
                        data: stats,
                        colors: colors,
                        resize: true,
                        formatter: function (x, data) {
                            return data.formatted;
                        }
                    });

                    data.month.types.length == 0 ? stats = [{
                        label: "Sin actividad",
                        value: 1,
                        formatted: "0 Tareas"
                    }] : stats = data.month.types;
                    data.month.types.length == 0 ? colors = ['#d46a6a'] : colors = data.month.colors;
                    Morris.Donut({
                        element: 'donut-month',
                        data: stats,
                        colors: colors,
                        resize: true,
                        formatter: function (x, data) {
                            return data.formatted;
                        }
                    });
                }, 1500);
                /* load tasks*/
                setTimeout(function () {
                    renderTasks(data.taskList);
                }, 2500);
                setTimeout(function () {
                    _loadIcon.addClass('hidden');
                }, 3500);
                connectSelector();
            });
            function getData(formatted) {
                $.ajax({
                    method: "POST",
                    url: "?r=admin/get-commercial-full-data",
                    async: false,
                    data: {
                        id: commercial
                    }
                }).done(function (data) {
                    return formatted(data);
                });
            }

            function resetView() {
                $('.donut-opt-block').each(function (idx, item) {
                    $(item).hasClass('hidden') ? $(item).removeClass('hidden') : null;
                });
                $('.donut').each(function (idx, item) {
                    $(item).hasClass('hidden') ? $(item).removeClass('hidden') : null;
                    $(item).empty();
                });
                $('.donut-showing').each(function (idx, item) {
                    $(item).html('Creadas');
                });
                $('#opened-list').empty();
                $('#closed-list').empty();
            }

            function connectSelector() {
                $('.donut-options').find('li').click(function () {
                    var _parent = $(this).parent(),
                        period = _parent.attr('key'),
                        state = $(this).attr('key'),
                        commercial = $('#commercial-selector').val();
                    _parent.parent().parent().find('.donut-showing').html($(this).text());
                    SPY.prototype.actions.getStatistics(commercial, period, state);
                });
            }

            function renderTasks(taskList) {
                var openedList = $('#opened-list'),
                    closedList = $('#closed-list'),
                    emptyList = '<div class="col-xs-12 empty-list"><div>Sin tareas</div></div>';
                if (taskList.opened.length > 0) {
                    taskList.opened.forEach(function (task) {
                        createTaskObj(task, true, function (obj) {
                            openedList.append(obj);
                        });
                    });
                } else {
                    openedList.append(emptyList);
                }
                if (taskList.closed.length > 0) {
                    taskList.closed.forEach(function (task) {
                        createTaskObj(task, false, function (obj) {
                            closedList.append(obj);
                        });
                    });
                } else {
                    closedList.append(emptyList);
                }
                $('[data-toggle="tooltip"]').tooltip();
                $('.task-expand').click(function () {
                    SPY.prototype.effects.slideAdminTask($(this));
                });
            }

            function createTaskObj(task, active, rendered) {
                var ArrayDate = task.alert.split(' ')[0].split('-'),
                    formattedDate = ArrayDate['2'] + "-" + ArrayDate[1],
                    obj;
                task.report==null ? task.report="" : null;
                obj = '<div class="col-xs-12 task-line">';
                obj += '<div class="task-time col-xs-2" data-toggle="tooltip" data-placement="right" title="Hora de ejecución">' + formattedDate + '</div>';
                task.type == "llamada de cortesía mensual"
                    ? obj += '<div class="task-type col-xs-3" style="background:' + task.color + ';" data-toggle="tooltip" data-placement="right" title="' + task.type + '">LL.C.M</div>'
                    : obj += '<div class="task-type col-xs-3" style="background:' + task.color + ';">' + task.type + '</div>';
                obj += '<div class="task-client col-xs-6">' + task.client + '</div>';
                obj += '<div class="col-xs-1">' +
                            '<div class="btn btn-default task-expand"><i class="fa fa-caret-down" aria-hidden="true"></i></div>' +
                        '</div>';
                obj += '<div class="col-xs-12 task-info">';
                obj += '<label class="col-xs-2">Asunto: </label><div class="col-xs-10 task-subject">' + task.subject + '</div>';
                if (active) {
                    obj += '<textarea class="col-xs-12 task-description" readonly>' + task.description + '</textarea>';
                } else {
                    obj += '<textarea class="col-xs-6 task-description" readonly>' + task.description + '</textarea>';
                    obj += '<label class="task-report-title col-xs-6">Reporte</label>';
                    obj += '<textarea class="col-xs-6 task-report" readonly>' + task.report + '</textarea>';
                }
                obj += '</div>';
                obj += '</div>';

                return rendered(obj);
            }
        },
        // load individual statistics
        getStatistics: function (commercial, period, state) {
            getIStatistics(period, state, commercial, function (data) {
                var donut = null,
                    stats = null,
                    colors = null,
                    empty = [{
                        label: "Sin actividad",
                        value: 1,
                        formatted: "0 Tareas"
                    }];
                switch (period) {
                    case "month":
                        donut = $('#donut-month');
                        break;
                    case "week":
                        donut = $('#donut-week');
                        break;
                    default:
                        donut = $('#donut-today');
                        break;
                }
                data.stats.length == 0 ? stats = empty : stats = data.stats;
                data.stats.length == 0 ? colors = ['#d46a6a'] : colors = data.colors;
                donut.empty();
                SPY.commons.loadIcon.removeClass('hidden');
                setTimeout(function () {
                    Morris.Donut({
                        element: donut.prop('id'),
                        data: stats,
                        colors: colors,
                        resize: true,
                        formatter: function (x, data) {
                            return data.formatted;
                        }
                    });
                    SPY.commons.loadIcon.addClass('hidden');
                }, 750);
            });

            function getIStatistics(period, filter, commercial, obj) {
                $.ajax({
                    method: "POST",
                    url: "?r=admin/get-statistic-data",
                    async: false,
                    data: {
                        'commercial': commercial,
                        'period': period,
                        'type': filter
                    }
                }).done(function (data) {
                    return obj(data);
                })
            }
        },
        // update guide text
        updateGuide: function (btn) {
            var text = btn.parent().parent().find('.guide-text');
            $.ajax({
                method: "POST",
                url: "?r=admin/update-guide",
                data: {
                    id: btn.attr('key'),
                    text: text.val()
                }
            }).done(function (confirm) {
                if (confirm == 1) {
                    alertify.notify("Guía actualizada");
                    text.css('background', 'lightgrey');
                    btn.addClass('disabled');
                } else {
                    alertify.error("No se ha podido contactar con el servidor")
                }
            })
        },
        // delete guide
        deleteGuide: function(guide){
            var guideId = guide.attr('key');
            alertify.confirm().setting({
                'title': "Spy | Advertencia",
                'message': "Esta operacion es irreversible. ¿Quieres continuar?",
                'default': "",
                'labels': {
                    'ok': "SÍ",
                    'cancel': "NO"
                },
                'movable': false,
                'modal': true,
                'closable': false,
                'transition': 'zoom',
                'onok': function () {
                    $.ajax({
                        method: "POST",
                        url: "?r=admin/delete-guide",
                        data: {
                            id: guideId
                        }
                    }).done(function (confirm) {
                        confirm == 1 ? location.reload() : alertify.error("No se ha podido contactar con el servidor");
                    })
                }
            }).show();
        },
        // finalize note
        finalizeNote: function(btn){
            var note = btn.parent(),
                note_id = note.attr('key');
            alertify.confirm().setting({
                'title': "Spy | Advertencia",
                'message': "¿Estás seguro?",
                'default': "",
                'labels': {
                    'ok': "SÍ",
                    'cancel': "NO"
                },
                'movable': false,
                'modal': true,
                'closable': false,
                'transition': 'zoom',
                'onok': function () {
                    $.ajax({
                        method: "POST",
                        url: "?r=admin/finalize-note",
                        data: {
                            id: note_id
                        }
                    }).done(function (confirm) {
                        confirm == 1 ? location.reload() : alertify.error("No se ha podido contactar con el servidor");
                    })
                }
            }).show();
        },
        // delete note
        deleteNote: function(btn){
            var note = btn.parent(),
                note_id = note.attr('key');
            alertify.confirm().setting({
                'title': "Spy | Advertencia",
                'message': "Esta operacion es irreversible. ¿Quieres continuar?",
                'default': "",
                'labels': {
                    'ok': "SÍ",
                    'cancel': "NO"
                },
                'movable': false,
                'modal': true,
                'closable': false,
                'transition': 'zoom',
                'onok': function () {
                    $.ajax({
                        method: "POST",
                        url: "?r=admin/delete-note",
                        data: {
                            id: note_id
                        }
                    }).done(function (confirm) {
                        confirm == 1 ? note.remove() : alertify.error("No se ha podido contactar con el servidor");
                    })
                }
            }).show();
        },
        // update note
        updateNote: function(btn){
            var note = btn.parent(),
                note_id = note.attr('key'),
                text = note.find('.note-text');
            $.ajax({
                method: "POST",
                url: "?r=admin/update-note",
                data: {
                    id: note_id,
                    text: text.val()
                }
            }).done(function (confirm) {
                if(confirm==1){
                    text.css('backgroundColor', 'lightgrey');
                    btn.addClass('disabled');
                } else {
                    alertify.error("No se ha podido contactar con el servidor");
                }
            })
        },
        // remove file of guide
        removeFile: function (btn, commercial) {
            var fileId = btn.attr('key'),
                item = btn.parent().parent(),
                url;
            commercial ? url = "?r=commercial/remove-file" : url = "?r=admin/remove-file";
            alertify.confirm().setting({
                'title': "Spy | Advertencia",
                'message': "Esta operacion es irreversible. ¿Quieres continuar?",
                'default': "",
                'labels': {
                    'ok': "SÍ",
                    'cancel': "NO"
                },
                'movable': false,
                'modal': true,
                'closable': false,
                'transition': 'zoom',
                'onok': function () {
                    $.ajax({
                        method: "POST",
                        url: url,
                        data: {
                            id: fileId
                        }
                    }).done(function (confirm) {
                        confirm == 1 ? item.remove() : alertify.error("No se ha podido contactar con el servidor");
                    })
                }
            }).show();
        },
        // update password
        updatePassword: function () {
            var form = $('#passwords-form'),
                current_pwd = $('#current-password'),
                new_pwd = $('#new-password'),
                new_pwd_v = $('#new-password_v'),
                _validate = SPY.prototype.validation;
            if (validateForm() && passConfirm()) {
                $.ajax({
                    method: "POST",
                    url: "?r=commercial/change-password",
                    data: {
                        current: current_pwd.val(),
                        new_pass: new_pwd.val()
                    }
                }).done(function (confirm) {
                    if (confirm == 1) {
                        alertify.notify("Contraseña actualizada");
                        setTimeout(function () {
                            location.reload();
                        }, 1000);
                    } else {
                        alertify.error("Tu contraseña actual no coincide");
                    }
                })
            }
            function validateForm() {
                var cnt = 0;
                form.find('.form-control').each(function (idx, input) {
                    if (_validate.isEmpty($(input))) {
                        $(input).css('border-color', '#471818');
                        cnt++;
                    } else {
                        $(input).css('border-color', 'rgb(0, 192, 23)')
                    }
                });
                return cnt == 0;
            }

            function passConfirm() {
                if (new_pwd.val() == new_pwd_v.val()) {
                    return true;
                } else {
                    new_pwd.css('border-color', '#471818');
                    new_pwd_v.css('border-color', '#471818');
                    alertify.error("Las contraseñas no coinciden");
                    return false;
                }
            }
        },
        // populate client list if waypoints creation
        populateMapClients: function(clear){
            var clientList = $('#mCSB_3_container'),
                added;
            if(!clear){
                $.ajax({
                    method: "POST",
                    url: "?r=commercial/get-geo-located-clients",
                    async: false
                }).done(function(list){
                    list.forEach(function(client){
                        client.route ? added = "on-route" : added = "";
                        var item = '<div class="col-xs-12 client-item" client-id="'+client.client_id+'" coordinates="'+client.coordinates+'">' +
                                        '<div class="col-xs-1 btn btn-default client-add"><i class="fa fa-plus" aria-hidden="true"></i></div>' +
                                        '<div class="col-xs-11 client-name '+added+'" data-toggle="tooltip" data-placement="left" title="'+client.name+'">'+client.name+'</div>' +
                                    '</div>';
                        clientList.append(item);
                    });
                    $('.client-add').click(function(){
                        SPY.prototype.actions.mapClientRoute($(this));
                    });
                    $('.client-name').tooltip();
                });
            } else {
                clientList.empty();
            }
        },
        // create route
        createRoute: function () {
            var exit = $('#exit-point'),
                destination = $('#destination-point'),
                destinationMultiple = $('#destination-multiple'),
                _validate = SPY.prototype.validation;
            if (_validate.isEmpty(exit)) {
                alertify.error("Indica un lugar de salida");
            } else {
                if (destinationMultiple.find('.fa-list').length===1) {
                    if (_validate.isEmpty(destination)) {
                        alertify.error("Indica el destino/destinos");
                    } else {
                        SPY.prototype.actions.generateRouteMap(exit.val(), destination.val());
                    }
                } else {
                    var clientList = $('#mCSB_3_container'),
                        clientName,
                        coordinates,
                        wayPoints = [];
                    clientList.find('.client-item').each(function(idx, client){
                        clientName = $(client).find('.client-name');
                        clientName.hasClass('on-route') ? wayPoints.push({
                            location: $(client).attr('coordinates'),
                            stopover: false
                        }) : null;
                    });
                    wayPoints.length > 1
                        ? SPY.prototype.actions.generateRouteMap(exit.val(), null, wayPoints)
                        : SPY.prototype.actions.generateRouteMap(exit.val(), wayPoints[0].location);
                }
            }
        },
        // maps add/delete of route
        mapClientRoute: function(client){
            var item = client.parent(),
                clientName = item.find('.client-name'),
                url;
            !clientName.hasClass('on-route') ? url = "?r=client/add-to-route" : url = "?r=client/delete-of-route";
            $.ajax({
                method: "POST",
                url: url,
                data: {
                    id: item.attr('client-id')
                }
            }).done(function (confirm) {
                if (confirm == 1) {
                    clientName.hasClass('on-route') ? clientName.removeClass('on-route') : clientName.addClass('on-route');
                } else {
                    alertify.error("No se ha podido contactar con el servidor");
                }
            });
        },
        // generate route map with params (exit, destination, list)
        generateRouteMap: function (exit, destination, list) {
            var map = $('#map'),
                loading = SPY.commons.loadIcon;
            loading.removeClass('hidden');
            setTimeout(function(){
                if(exit == "0.0, 0.0"){
                    var destinationA = exit.split(', ');
                    exit = [destinationA[0].replace('"',''), destinationA[1].replace('"','')];
                }
                if (list == undefined) {
                    map.gmap3('get').route({
                        origin: exit,
                        destination: destination,
                        travelMode: google.maps.DirectionsTravelMode.DRIVING
                    }).directionsrenderer(function (results) {
                        if (results) {
                            return {
                                panel: "#mCSB_2_container",
                                directions: results,
                                preserveViewPort: true,
                                draggable: true
                            }
                        } else {
                            alertify.error("Alguna de las direcciones no es lo suficientemente específica");
                        }
                    });
                }
                else {
                    alertify.error('Este servicio no está disponible para múltiples destinos. Solo es posible obtener rutas punto a punto.', 0);
                    //TODO
                 /*   var dest = list[list.length-1];
                       /!* finalList = list.splice(-1, 1);*!/
                    map.route({
                        origin: exit,
                        waypoints: list,
                        destination: dest.location,
                        travelMode: google.maps.DirectionsTravelMode.DRIVING
                    }).directionsrenderer(function (results) {
                        if (results) {
                            return {
                                panel: "#mCSB_2_container",
                                directions: results
                            }
                        } else {
                            alertify.error("Alguna de las direcciones no es lo suficientemente específica");
                        }
                    });*/
                }
                loading.addClass('hidden');
            }, 1500);
            alertify.notify('Recarge la página si necesita generar una nueva ruta.', 30);
        },
        // show commercial list
        showCommercials: function(){
            $.ajax({
                method: "POST",
                url: "?r=admin/get-commercials-list",
                async: false
            }).done(function(list){
                var content = '<div id="commercial-modal-list" class="col-xs-12">';
                    content += '<div class="col-xs-12 commercial-title">';
                    content += '<div class="col-xs-8">Comercial</div>';
                    content += '<div class="col-xs-4">Contrase&ntilde;a</div>';
                    content += '</div>';
                list.forEach(function(commercial){
                    content += '<div class="col-xs-12 commercial-item">';
                        content += '<div class="col-xs-8">'+commercial.name+' '+commercial.lastname+'</div>';
                        content += '<div class="col-xs-4">'+commercial.password+'</div>';
                    content += '</div>';
                });
                content += '</div>';
                alertify.alert().setting({
                    'title': "Spy | Comerciales ",
                    'message': content,
                    'default': "",
                    'label': "CERRAR",
                    'movable': false,
                    'modal': true,
                    'closable': false,
                    'transition': 'zoom'
                }).show();
            });
        }
    }
};

$(function () {
    SPY.init();
});