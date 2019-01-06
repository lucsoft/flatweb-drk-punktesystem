<?php

//DISCLAIMER: due to lack of time the code is not 100% optimal








//CONFIG
$title = "DRK-Furtwangen e.V.: Punktesystem";
$navname = "Punktesystem 2019";
$flatweb_version = "beta";
$database_url = "";

$web_title = "Punktesystem";
$web_subtitle = "Vom DRK OV Furtwangen e. V.";

$nav = '[{"title": "Hallo <b id=\'replacename\'></b>", "fun": "loadpage(\'myacc\')", "style": "left"},{"title": "' . $navname . '", "fun": "", "style": "center"}]';
$theme = 'white';
?>
<!DOCTYPE html>
<html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?php echo $title; ?></title>
        <script src="https://api.lucsoft.de/flatweb/<?php echo $flatweb_version; ?>/webgen"></script>
        <script src="https://api.lucsoft.de/flatweb/<?php echo $flatweb_version; ?>/jquery.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Material+Icons|Roboto:200,300,100" rel="stylesheet">
        <link rel="stylesheet" href="https://api.lucsoft.de/flatweb/<?php echo $flatweb_version; ?>/init.php">
        <theme><link rel="stylesheet" href="https://api.lucsoft.de/themes/FlatWeb/css/<?php echo $theme;?>.css"></theme>
        <script>    
            loadModule("lucsoft.database", () => {
                database.apiurl = "<?php echo $database_url; ?>"; 
                loadHome();
             });
        </script>
    </head>
    <body class="FlatWeb">
    <script>
            createNav(`<?php echo $nav; ?>`, "style_box");
    </script>
        <article id="page" class="">
        </article>
        <script>
            $('nav').css("opacity", "0");
            function funcLogin() {
                $('nav').css("opacity", "0");
                removeLastElement(() => {});
                addElement("loginwindow", {text: {password: "Kennwort", email: "Email-Addresse",button: "Anmelden", text:"Anmelden"},
                beforelogin: () => {
                    $('#loginerrormsg').css("color", "black");
                    $('#loginerrormsg').css("margin", "1.6rem");
                    $('#loginerrormsg').css("font-weight", "300");
                    $('#loginerrormsg').html("Anmeldung Läuft...");  
                },afterlogin: () => {
                    loadHome();
                    $('nav').css("opacity", "100");
                },onError: () => {
                    $('#loginerrormsg').css("color", "red");
                    $('#loginerrormsg').css("margin", "1rem");
                    $('#loginerrormsg').css("font-weight", "600");
                    
                    $('#loginerrormsg').html("Deine Anmeldedaten sind falsch");
                }}, (e)=> { 
                });
                newline();
                newline();
                newline();
                godown();
            }
            function loadImprint(onback) {
                clearElements();
                addElement("titlew",{
                    title: "Punktesystem",
                    subtitle: "Vom DRK OV Furtwangen e. V.",
                    subtitleposx: "0rem"
                }, () => {});
                    addElement("window", {
                    content: `<span class="text">Idee<br>Tim Sauter<br><br>Design und Entwicklung<br><a href="https://lucsoft.de" title="lucsoft" target="_blank">lucsoft</a><br><br>Weitere Informationen erhalten Sie <a href="https://www.drk-furtwangen.de/footer-menue-deutsch/service/impressum.html" title="Impressum" target="_blank">hier</a>.</span>`,
                    text:
                    {
                        title: "Impressum",
                    },
                        buttons: [
                            {
                                text:"Zurück",
                                color:"red",
                                onclick: (ez) => {
                                    loadHome();
                                }
                            }
                        ]
                    },
                    () => {

                    }
                );

            }
            $('.infoicon').html("Developed by lucsoft");
            function loadSearch () {
                clearElements();
                addElement("titlew",{
                    title: "Punktesystem",
                    subtitle: "Vom DRK OV Furtwangen e. V.",
                    subtitleposx: "0rem"
                }, () => {});
                    var searchbox;
                database.getAccounts((index) => {
                    searchbox = addElement("searchbox",{
                        searchtext:"Nach einer Person suchen...",
                        index: index.users,
                        error: {
                            notfound: "Kein Treffer"
                        },onsearch: () => {
                            godown(false);
                        },onclose:() => {
                            loadHome();
                        }
                    },(e) => { e.input.focus();});
                    newline();
                    newline();
                    newline();
                    godown();
                });
                
                oncommand = (wg) => {
                    if(wg.startsWith(searchbox.id+ "#")) {
                        clearElements();
                        addElement("titlew",{
                            title: "Punktesystem",
                            subtitle: "Vom DRK OV Furtwangen e. V.",
                            subtitleposx: "0rem"
                        }, () => {});
                        database.getAccount(wg.split('#')[1], (user) => {
                            if (user.id == database.account.id) {
                                addElement("window", {
                                    content: `
                                    <list class="style2 nomargin">
                                        <item>Straße <span class="right">${user.street}</span></item>
                                        <item>Admin <span class="right">${user.admin}</span></item>
                                        <item>Momentare Punkte <span class="right">${user.currentpoints}</span></item>
                                        <item>Erstellt <span class="right">${timeAgoDE(Date.parse(user.createdate))}</span></item>
                                        <item>Email <span class="right">${user.email}</span></item>
            
                                    </list>`,
                                    text:
                                    {
                                        title: user.name[0] + " " + user.name[1],
                                    },
                                        buttons: [
                                            {
                                                text:"Punkteverlauf anzeigen",
                                                color:"",
                                                onclick: (e) => {
                                                    e.hide();
                                                    var list2 = "";
                                                    user.pointshistory.forEach((g) => {
                                                        if(g.value >= 1) {
                                                            list2 += `<item>${g.text} <span class="right">+${g.value}</span></item>`;

                                                        } else {
                                                            list2 += `<item>${g.text} <span class="right">  ${g.value}</span></item>`;

                                                        }
                                                    });
                                                    addElement("window", {
                                                            content: `
                                                            <list class="style2 nomargin">
                                                                ${list2}
                                                            </list>`,
                                                            text:
                                                            {
                                                                title: "Punkteverlauf",
                                                            },
                                                            max_width: "42rem",
                                                            buttons: [
                                                                {
                                                                    text:"Neuer Eintrag",
                                                                    color:"",
                                                                    onclick: (z) => {
                                                                        z.hide();
                                                                        addElement("window", {
                                                                                content: `<list class="style2 nomargin">
                                                                                    <item>Anzahl <span class="right"><input id="input_newpoints" type="text" placeholder="+10"></item>
                                                                                    <item>Grund <span class="right"><input id="input_newtext" type="text" placeholder="z.B. Gutschrift für Arbeit"></span></item>
                                                                                </list>`,
                                                                                text:
                                                                                {
                                                                                    title: "Neuer Eintrag",
                                                                                },
                                                                                buttons: [
                                                                                    {
                                                                                        text:"Okay",
                                                                                        color:"",
                                                                                        onclick: (g) => {
                                                                                            
                                                                                            user.pointshistory.push({value: $('#input_newpoints').val().replace('+', ''),text:$('#input_newtext').val(),id: database.account.id});
                                                                                            var points = 0;
                                                                                            user.pointshistory.forEach((g) => {
                                                                                                points += Number.parseInt(g.value);
                                                                                            });
                                                                                            database.editAccount({id: user.id, currentpoints: points, pointshistory: JSON.stringify(user.pointshistory) },() => {});
                                                                                            g.hide();
                                                                                            z.show();
                                                                                            
                                                                                        }
                                                                                    },
                                                                                    {
                                                                                        text:"Zurück",
                                                                                        color:"red",
                                                                                        onclick: (g) => {
                                                                                            loadSearch();
                                                                                        }
                                                                                    }
                                                                                ]
                                                                            },
                                                                            () => {                        }
                                                                        );
                                                                    }
                                                                },
                                                                {
                                                                    text:"Zurück",
                                                                    color:"",
                                                                    onclick: (f) => {
                                                                        f.hide();
                                                                        e.show();
                                                                    }
                                                                }
                                                            ]
                                                        },
                                                        () => {

                                                        }
                                                    );
                                                }
                                            },
                                            {
                                                text:"Passwort zurücksetzten",
                                                color:"",
                                                onclick: async (g) => {
                                                    g.hide();
                                                    $("#page").append(`<div class="loadingwheel"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><span class="text">Account wird resetet...</text></div>`);
                                                    database.resetPasswordAccount({email: user.email});
                                                    await sleep(500);
                                                    g.show();
                                                    removeLastElement();
                                                }
                                            },
                                            {
                                                text:"Zurück",
                                                color:"red",
                                                onclick: () => {
                                                    loadSearch();
                                                }
                                            }
                                        ]
                                    },
                                    () => {

                                    }
                                );
                                newline();
                                newline();
                                newline();
                                godown();
                            } else {
                                addElement("window", {
                                    content: `
                                    <list class="style2 nomargin">
                                        <item>Straße <span class="right">${user.street}</span></item>
                                        <item>Admin <span class="right">${user.admin}</span></item>
                                        <item>Momentare Punkte <span class="right">${user.currentpoints}</span></item>
                                        <item>Erstellt am <span class="right">${timeAgoDE(Date.parse(user.createdate))}</span></item>
                                        <item>Email <span class="right">${user.email}</span></item>
                                    </list>`,
                                    text:
                                    {
                                        title: user.name[0] + " " + user.name[1],
                                    },
                                        buttons: [
                                            {
                                                text:"Punkteverlauf anzeigen",
                                                color:"",
                                                onclick: (e) => {
                                                    e.hide();
                                                    var list2 = "";
                                                    user.pointshistory.forEach((g) => {
                                                        if(g.value >= 1) {
                                                            list2 += `<item>${g.text} <span class="right">+${g.value}</span></item>`;

                                                        } else {
                                                            list2 += `<item>${g.text} <span class="right">  ${g.value}</span></item>`;

                                                        }
                                                    });
                                                    addElement("window", {
                                                            content: `
                                                            <list class="style2 nomargin">
                                                                ${list2}
                                                            </list>`,
                                                            text:
                                                            {
                                                                title: "Punkteverlauf",
                                                            },
                                                            max_width: "42rem",
                                                            buttons: [
                                                                {
                                                                    text:"Neuer Eintrag",
                                                                    color:"",
                                                                    onclick: (z) => {
                                                                        z.hide();
                                                                        addElement("window", {
                                                                                content: `<list class="style2 nomargin">
                                                                                    <item>Anzahl <span class="right"><input id="input_newpoints" type="text" placeholder="+10"></item>
                                                                                    <item>Grund <span class="right"><input id="input_newtext" type="text" placeholder="z.B. Gutschrift für Arbeit"></span></item>
                                                                                </list>`,
                                                                                text:
                                                                                {
                                                                                    title: "Neuer Eintrag",
                                                                                },
                                                                                buttons: [
                                                                                    {
                                                                                        text:"Okay",
                                                                                        color:"red",
                                                                                        onclick: (g) => {
                                                                                            
                                                                                            user.pointshistory.push({value: $('#input_newpoints').val().replace('+', ''),text:$('#input_newtext').val(),id: database.account.id});
                                                                                            var points = 0;
                                                                                            user.pointshistory.forEach((g) => {
                                                                                                points += Number.parseInt(g.value);
                                                                                            });
                                                                                            database.editAccount({id: user.id, currentpoints: points, pointshistory: JSON.stringify(user.pointshistory) },() => {});
                                                                                            g.hide();
                                                                                            z.show();
                                                                                            
                                                                                        }
                                                                                    },
                                                                                    {
                                                                                        text:"Zurück",
                                                                                        color:"",
                                                                                        onclick: (g) => {
                                                                                            loadSearch();
                                                                                        }
                                                                                    }
                                                                                ]
                                                                            },
                                                                            () => {                        }
                                                                        );
                                                                    }
                                                                },
                                                                {
                                                                    text:"Zurück",
                                                                    color:"",
                                                                    onclick: (f) => {
                                                                        f.hide();
                                                                        e.show();
                                                                    }
                                                                }
                                                            ]
                                                        },
                                                        () => {

                                                        }
                                                    );
                                                }
                                            },
                                            {
                                                text:"Passwort zurücksetzten",
                                                color:"",
                                                onclick: async (g) => {
                                                    g.hide();
                                                    $("#page").append(`<div class="loadingwheel"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><span class="text">Account wird resetet...</text></div>`);
                                                    database.resetPasswordAccount({email: user.email});
                                                    await sleep(500);
                                                    g.show();
                                                    removeLastElement();
                                                }
                                            },
                                            {
                                                text:"Löschen",
                                                color:"red",
                                                onclick: async () => {
                                                    removeLastElement();
                                                    removeLastElement();
                                                    removeLastElement();
                                                    removeLastElement();
                                                    $("#page").append(`<div class="loadingwheel"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><span class="text">Account wird gelöscht...</text></div>`);
                                                    database.deleteAccount(user.id,() => { });
                                                    await sleep(500);
                                                    loadSearch();
                                                }
                                            },
                                            {
                                                text:"Zurück",
                                                color:"red",
                                                onclick: () => {
                                                    loadSearch();
                                                }
                                            }
                                        ]
                                    },
                                    () => {

                                    }
                                );
                                newline();
                                newline();
                                newline();
                                godown();

                            }
                        });
                    }
                };
                            
                            
            }
            function loadCreateUser() {
                clearElements();
                addElement("titlew",{
                    title: "Punktesystem",
                    subtitle: "Vom DRK OV Furtwangen e. V.",
                    subtitleposx: "0rem"
                }, () => {});
                formwindow = addElement("window", {
                    content: `
                        <list id="form_createuser" class="style2 nomargin">
                            <item>Vorname <span class="right"><input id="input_firstname" type="text" placeholder="Max"></span></span></item>
                            <item>Nachname <span class="right"><input id="input_lastname" type="text" placeholder="Musterman"></span></item>
                            <item>Email <span class="right"><input id="input_email" type="email" placeholder="Email-Addresse"></span></item>
                            <item>Straße <span class="right"><input id="input_street" type="text" placeholder="Hauptstraße 20"></span></item>
                            <item>Admin <span class="right"><switch onclick="$('#input_admin_i').toggleClass('active');$('#input_admin').click();" id="input_admin_i"></switch><input id="input_admin" class="hide"type="checkbox" ></span></item>
                        </list>`,
                    text:
                    {
                        title: "Neues Konto eröffnen",
                    },
                        buttons: [
                            {
                                text:"Hinzufügen",
                                color:"",
                                onclick: (func) => {
                                    if($('#input_firstname').val() == "" || $('#input_email').val() == "" || $('#input_street').val() == "") {
                                        addElement("window", {
                                            content: `<span class="text">Bitte geben sie Vorname, Email und Straße an!</span>`,
                                            text: { title: "Fehler" },
                                            buttons: [
                                                {
                                                    text:"Okay",
                                                    color:"",
                                                    onclick: (func2) => {
                                                        func2.hide();
                                                        func.show();
                                                    }
                                                },
                                                {
                                                    text:"Abbrechen",
                                                    color:"red",
                                                    onclick: () => {
                                                        loadHome();
                                                    }
                                                }
                                            ]
                                        },
                                        () => { });
                                        func.hide();
                                    } else {
                                        func.hide();
                                        $("#page").append(`<div class="loadingwheel"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><span class="text">Account wird Erstellt...</text></div>`);
                                        database.createAccount({
                                            name: [
                                                $("#input_firstname").val(),
                                                $("#input_lastname").val()
                                            ],
                                            admin: $("#input_admin")[0].checked,
                                            street: $("#input_street").val(),
                                            email:$('#input_email').val(),
                                        }, (database.userinfo.user.name[0] + database.userinfo.user.name[1]), async (e) => {
                                            if(e.error == "userexist") {
                                                await sleep(500);
                                                removeLastElement();
                                                addElement("window", {
                                                    content: `<span class="text">Der Account wurde nicht erstellt. Diese Email ist in Benutzung!</span>`,
                                                    text: { title: "Fehler" },
                                                    buttons: [
                                                        {
                                                            text:"Verstanden",
                                                            color:"",
                                                            onclick: (func3) => {
                                                                func3.hide();
                                                                func.show();
                                                            }
                                                        }
                                                    ]
                                                },
                                                () => { });
                                            } else {        
                                                removeLastElement();
                                                addElement("window", {
                                                    content: `<span class="text">Der Account wurde erstellt. Es wurde eine Email an den neuen Benutzer gesendert mit seinem Passwort!</span>`,
                                                    text: { title: "Bestätigung" },
                                                    buttons: [
                                                        {
                                                            text:"Verstanden",
                                                            color:"",
                                                            onclick: (func2) => {
                                                                loadHome();
                                                            }
                                                        }
                                                    ]
                                                },
                                                () => { });
                                            }
                                        });
                                    }
                         
                                    
                                   
                                }
                            },
                            {
                                text:"Abbrechen",
                                color:"red",
                                onclick: () => {
                                    loadHome();
                                }
                            }
                        ]
                    },
                    () => {

                    }
                );
                newline();
                newline();
                newline();
                godown();
            }
            function loadAboutMe() {
                clearElements();
                        addElement("titlew",{
                            title: "Punktesystem",
                            subtitle: "Vom DRK OV Furtwangen e. V.",
                            subtitleposx: "0rem"
                        }, () => {});
                database.getAccount(database.account.id, (e)=>{
                    addElement("window", {
                            content: `<list class="style2 nomargin">
                                <item>Name <span class="right">${e.name[0] + " " +  e.name[1]}</span></item>
                                <item>Straße <span class="right">${e.street}</span></item>
                                <item>Momentare Punkte <span class="right">${e.currentpoints}</span></item>
                                <item>Email <span class="right">${e.email}</span></item>
                                <item>Account Erstellung:<span class="right">${timeAgoDE(Date.parse(e.createdate))}</span></item>
                                <item>Theme <span class="right">${e.theme}</span></item>
                            </list>`,
                            text:
                            {
                                title: "Deine Statistik",
                            },
                            buttons: [
                                {
                                    text:"Punkteverlauf anzeigen",
                                    color:"",
                                    onclick: (c) => {
                                        c.hide();
                                        var list2 = "";
                                        e.pointshistory.forEach((g) => {
                                            if(g.value >= 1) {
                                                list2 += `<item>${g.text} <span class="right">+${g.value}</span></item>`;
                                            } else {
                                                list2 += `<item>${g.text} <span class="right">  ${g.value}</span></item>`;
                                            }
                                        });
                                        addElement("window", {
                                                content: `
                                                <list class="style2 nomargin">
                                                    ${list2}
                                                </list>`,
                                                text:
                                                {
                                                    title: "Punkteverlauf",
                                                },
                                                max_width: "42rem",
                                                buttons: [
                                                    {
                                                        text:"Zurück",
                                                        color:"",
                                                        onclick: (f) => {
                                                            f.hide();
                                                            c.show();
                                                            godown();
                                                        }
                                                    }
                                                ]
                                            },
                                            () => {
                                            }
                                        );
                                    }
                                },
                                {
                                    text:"Zurück",
                                    color:"red",
                                    onclick: () => {
                                        loadHome();
                                    }
                                }
                            ]
                        },
                        () => {                        }
                    );
                    newline();
                    newline();
                    newline();
                    godown();
                
                });
            }
            function loadSettingThing(what) {
                if (what == "Name") {
                    
                }
            }
            function toggleTheme() {
                if(getTheme() == "white") {
                    database.editMyAccount({theme:"dark"},() => {});
                } else {
                    database.editMyAccount({theme:"white"},() => {});
                }
            }
            function loadSettings() {
                clearElements();
                addElement("titlew",{
                    title: "Punktesystem",
                    subtitle: "Vom DRK OV Furtwangen e. V.",
                    subtitleposx: "0rem"
                }, () => {});
                    database.getAccount(database.account.id, (e)=>{ 
                        addElement("window", {
                            content: `<list class="style2 nomargin">
                            <item>Vorname <span class="right"><input autocomplete="false" id="input_firstname" type="text" placeholder="Max" value="${e.name[0]}"></span></span></item>
                            <item>Nachname <span class="right"><input autocomplete="false" id="input_lastname" type="text" placeholder="Musterman" value="${e.name[1]}"></span></item>
                            <item>Email <span class="right"><input autocomplete="false" id="input_email" type="email" placeholder="Email-Addresse" value="${e.email}"></input></span></item>
                            <item>Straße <span class="right"><input autocomplete="false" id="input_street" type="text" placeholder="Hauptstraße 20" value="${e.street}"></span></item>
                            <item>Passwort <span class="right"><input autocomplete="false" id="input_password" type="password" placeholder="password" value="${database.account.password.substr(0,8)}"></span></item>
                            <item>Passwort Wiederholen <span class="right"><input autocomplete="false" id="input_passwordr" type="password" placeholder="password" value="${database.account.password.substr(0,8)}"></span></item>
                            <item>Theme <span class="right"><button onclick="toggleTheme()">Wechseln</button></span></item>
                            
                            </list>`,
                            text:
                            {
                                title: "Einstellung",
                            },
                            buttons: [
                                {
                                    text:"Speichern",
                                    color:"red",
                                    onclick: () => {
                                        if($('#input_password').val() != database.account.password.substr(0,8)) {
                                            if($('#input_password').val() == $('#input_passwordr').val()) {
                                                database.editMyAccount({password: SHA256($('#input_password').val())}, () => {});    
                                                    location.reload();
                                            }
                                        }
                                        if($('#input_firstname').val() != e.name[0] ||  $('#input_email').val() != e.email || $('#input_street').val() != e.street || $('#input_lastname').val() != e.name[1]) {
                                            database.editMyAccount({firstname:$('#input_firstname').val(), email: $('#input_email').val(), street: $('#input_street').val(), lastname: $('#input_lastname').val()}, () => {});
                                        } else      {
                                            loadHome();    
                                        }
                                    }
                                },
                                {
                                    text:"Zurück",
                                    color:"",
                                    onclick: () => {
                                        loadHome();
                                    }
                                }
                            ]
                        },
                        () => {                        }
                        );
                        newline();
                        newline();
                        newline();
                        godown();
                    });
                        
            }
            function loadHome() {
                clearElements();
                addElement("titlew",{
                    title: "Punktesystem",
                    subtitle: "Vom DRK OV Furtwangen e. V.",
                    subtitleposx: "0rem"
                }, () => {});
                if(database.loggedIn) {
                    database.getAccount(database.account.id, (e)=>{ 
                        if(e.admin) {
                            addElement("buttons",{big: true,list:[{text: "Suchen", onclick: "loadSearch()"},{text: "Deine Statistik", onclick: "loadAboutMe()"},{text:"Einstellungen",onclick:"loadSettings()"},{text:"Benutzer Hinzufügen", onclick: "loadCreateUser()"},{text: "Impressum",onclick:'loadImprint()'}]}, () => {});
                        } else {
                            addElement("buttons",{big: true,list:[{text: "Deine Statistik", onclick: "loadAboutMe()"},{text:"Einstellungen",onclick:"loadSettings()"},{text: "Impressum",onclick:'loadImprint()'}]}, () => {});
                        }
                    }); 

                } else {
                    addElement("buttons",{big: true,list:[{text: "Anmelden", onclick: 'funcLogin()'},{text: "Impressum",onclick:'loadImprint()'}]}, () => {});
                }
            } 
        </script>
        
    </body>
</html>
