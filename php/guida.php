<?php 
    session_start(); 
    require_once "includes/profileInfo.inc.php";
    $n_notifications = isset($_SESSION['NomeUtente']) ? getNotifications($_SESSION['NomeUtente']) : 0;


?>
<!DOCTYPE html>
<html lang="it">
    <head>
        <title>LongLight - Guida</title>
        <meta charset="UTF-8"/>
        <link href="../css/style.css" rel="stylesheet" type="text/css"/>
    </head>
    <body>
        <header>
            <h1>LongLight</h1>
        </header>
        <!-- Nav differs if user logged in or not -->
        <?php if (!isset($_SESSION['NomeUtente'])): ?> <!-- If user is not logged in -->
            <nav class="nav4">
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li class="current_page"><a href="guida.php">Guida</a></li>
                    <li><a href="../signup.html">Signup</a></li>
                    <li><a href="../login.html">Login</a></li>
                </ul>
            </nav>
        <?php else: ?> <!-- If user is logged in -->
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li class="current_page"><a href="guida.php">Guida</a></li>
                    <li><a href="profile.php?id=<?=$_SESSION['NomeUtente']?>">Profilo</a></li>
                    <li><a href="includes/logout.inc.php">Logout</a></li>
                    <?php if ($n_notifications == 0): ?>
                        <li><a href="notifiche.php?id=<?=$_SESSION['NomeUtente']?>">Notifiche</a></li>
                    <?php else: ?>
                        <li><a href="notifiche.php?id=<?=$_SESSION['NomeUtente']?>">Notifiche<sup>(<?= $n_notifications; ?>)</sup></a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        <?php endif; ?>
        <main>
            <article>
                    <header><h2>Il progetto LongLight</h2></header>
                    <section>
                        <p>
                            Spesso le tecnologie radio sono ritenute superate da telefonia, 
                            televisione ed Internet; eppure hanno i propri pregi e campi di utilizzo, 
                            sopravvivono, prosperano e raccolgono gruppi di appassionati; 
                            tali appassionati possono ascoltare contenuti, 
                            comunicare con altri utenti ed operare stazioni amatoriali per creare loro stessi 
                            contenuti radio. Tuttavia una radio ha come fondamentale caratteristica la 
                            comunicazione sincrona, che si rivela uno svantaggio in certe situazioni.
                            E qui entra in gioco una controparte asincrona: "Long Light", un social media 
                            creato nel 2024 per diffondere la passione della radioamatoria e connettere chi la 
                            condivide. Il sito accetta chiunque, anche chi non possiede 
                            ancora un apparato radiofonico. Questa guida ha lo scopo di chiarire i maggiori dubbi 
                            che un utente potrebbe avere al riguardo.
                        </p>
                    </section>
            </article>
            <article>
                    <header><h2>Domande e risposte sulla radiomatoria</h2></header>
                    <section>
                        <h3>Cosa vuol dire Long Light?</h3>
                        <p>
                            Letteralmente "Luce Lunga", fa riferimento al fatto che le frequenze radio sono 
                            radiazioni luminose con un'elevata lunghezza d'onda.
                        </p>
                        <figure>
                            <img src="../img/onde.jpg" alt=""/>
                            <figcaption>
                                Le frequenze usate da radioamatori sono solitamente indicate in MegaHertz (MHz), 
                                ossia un milione di segnali al secondo.
                            </figcaption>
                        </figure>
                    </section>
                    <section>
                        <h3>Cosa spinge a diventare radioamatori?</h3>
                        <p>
                            L'archetipo del radioamatore raccoglie essenzialmente due tipi di persone: 
                            l'operatore che ama i collegamenti a lunga distanza ed il tecnico che studia la 
                            tecnologia fondamento dei sistemi di comunicazione. Il mondo dei radioamatori 
                            racchiude al suo interno tecnologie evolute come quelle militari e di uso comune 
                            come quelle dei cellulari; il fascino riguarda principalmente garantire collegamenti 
                            dove le tecnologie offerte al pubblico non riescono.
                        </p>
                    </section>
                    <section>
                        <h3>Il radioamatore ha un gergo tecnico strutturato?</h3>
                        <p>
                            Si utilizza un sistema di identificazione internazionale chiamato 
                            <a href="https://www.radioamatore.info/codice-q-cw-etc/codice-q.html">Codice Q</a>: 
                            un elenco di espressioni composte da 3 lettere con una Q iniziale, che esprimono 
                            diverse situazioni in cui si potrebbe trovare l'operatore.
                        </p>
                        <p>Esempi:</p>
                        <dl>
                            <dt>QRZ</dt>
                            <dd>Qualcun'altro in linea?</dd>
                            <dt>QSY</dt>
                            <dd>Cambio frequenza.</dd>
                            <dt>QRT</dt>
                            <dd>Chiudo la comunicazione.</dd>
                        </dl>
                    </section>
                    <section>
                        <h3>Si tratta di un hobby costoso?</h3>
                        <p>
                            La dismissione di attrezzatura da parte degli operatori telefonici ha immesso nel 
                            mercato dell'usato apparecchiature e materiale a basso costo ed un radioamatore 
                            esperto potrebbe ulteriormente ridurre i costi costruiendo gli apparati che utilizza.
                            In base al tipo di frequenze adoperate un sistema radio completo costerebbe da poche 
                            centinaia a diverse migliaia di euro, ma un utente medio con esigenze limitate 
                            difficilmente si ritroverebbe a pagare oltre 100-150 euro.
                        </p>
                        <figure>
                            <img src="../img/conventionari.png" alt="Convention ARI"/>
                            <figcaption>
                                Una convention organizzata dall'<abbr title="Associazione Radioamatori Italiani">ARI</abbr>. 
                                I radioamatori le frequentano spesso per i mercatini di scambio.
                            </figcaption>
                        </figure>
                    </section>
                    <section>
                        <h3>Le frequenze sono molto utilizzate?</h3>
                        <p>
                            Contrariamente a quanto si pensa, al giorno d'oggi lo spettro radiofonico si 
                            presenta tutt'altro che spoglio: ci sono diversi servizi attivi, soprattutto 
                            nelle frequenze molto alte (<abbr title="Very High Frequencies">VHF</abbr> e 
                            specialmente <abbr title="Ultra High Frequencies">UHF</abbr>); il solo modem 
                            Wi-Fi oggi utilizza frequenze sui 2,4GHz e sui 5GHz, senza parlare dei 
                            "trasferimenti digitali" che spesso operano sui 24GHz e oltre.
                            L'evoluzione consiste nel progressivo abbandono dei segnali di tipo analogico in 
                            favore del digitale.
                        </p>
                    </section>
                    <section>
                        <h3>Quale frequenza scegliere per comunicare</h3>
                        <p>
                            I radioamatori devono operare solo nei range di frequenze a loro assegnati, 
                            anche se molto vicini a range privati (satelliti, televisione, stazioni radio 
                            professionali, altri radioamatori ecc.) o addirittura proibiti.
                            Esistono frequenze pubbliche, ma solo un radioamatore patentato possiede 
                            frequenze ad uso esclusivo.
                        </p>
                        <figure>
                            <img src="../img/triomedusa.jpg" alt="Trio Medusa"/>
                            <figcaption>
                                I comici del "Trio Medusa" iniziarono la loro carriera con un'emittente radiofonica pirata, 
                                trasmettendo i propri sketch sopra frequenze private altrui; in seguito passarono a metodi legali.
                            </figcaption>
                        </figure>
                    </section>
                    <section>
                        <h3>Come si ottiene una propria frequenza?</h3>
                        <p>
                            Per essere radioamatore bisogna sostenere un esame ministeriale di radiotecnica e 
                            normative sull'utilizzo delle frequenze. Solo dopo aver ottenuto la patente diviene 
                            possibile richiedere la licenza per ottenere il nominativo internazionale di 
                            Operatore di Stazione Radioamatoriale.
                        </p>
                        <figure>
                            <img src="../img/montecavallo.jpg" alt="Costruzione ponte radio"/>
                            <figcaption>Il ponte ripetitore di Monte Cavallo, Cesena, costruito dal team di 
                                radioamatori IR4UBA e tuttora usato per telecomunicazioni - non solo radioamatoriali. 
                                In seguito, componenti della squadra aiutarono la Protezione Civile nel brillamento di un 
                                ordigno esplosivo.
                            </figcaption>
                        </figure>
                    </section>
                    <section>
                        <h3>La figura del radiamatore viene valutata?</h3>
                        <p>
                            Normalmente i radioamatori vengono chiamati ad operare assieme alla Protezione Civile 
                            in quanto sono gli unici in grado di intervenire nell'immediatezza e con risorse 
                            disponibili in caso di disastro.
                        </p>
                    </section>
            </article>
            <aside>
                    <header><h2>Regole</h2></header>
                    <ul>
                        <li>Non si impone un registro formale, ma si consiglia fortemente di aderire alle regole dell'ortografia e della grammatica e di astenersi dal turpiloquio.</li>
                        <li>Raccomandazioni finalizzate ad invogliare all'acquisto di prodotti o servizi fuori tema rispetto allo scopo del sito saranno considerate spam.</li>
                        <li>Raccomandazioni finalizzate ad invogliare all'acquisto di prodotti o servizi in tema rispetto allo scopo del sito saranno considerate spam se insistenti od assidue.</li>
                        <li>Post umoristici non costituiranno materiale accettabile se eccessivamente indecenti od ancorati al tema del sito in maniera forzata.</li>
                        <li>Si sconsiglia la diffusione di informazioni, soprattutto tecniche, sulla cui accuratezza non si abbia certezza.</li>
                        <li>Non si dovranno diffamare utenti o pretendere la rivelazione di informazioni personali.</li>
                        <li>Non si dovranno incoraggiare usi illeciti delle apparecchiature radiofoniche, metodi truffaldini per appropriarsi di frequenze o violazioni della legge in generale.</li>
                        <li>Le immagini non dovranno presentare in maniera chiaramente visibile contenuti che potrebbero impressionare un utente.</li>
                        <li>Commenti negativi verso prodotti, aziende od infrastrutture dovranno essere scritti con educazione da una prospettiva neutrale e dovranno riportare i fatti concreti su cui si basano.</li>
                    </ul>
            </aside>
            <?php if (!isset($_SESSION['NomeUtente'])): ?>
                <aside>
                    <header><h2>Invito</h2></header>
                    <p>
                        Speriamo che questa pagina ti abbia aiutato a vedere le tecnologie radiofoniche in una 
                        luce diversa e ti invitiamo a registrarti al sito seguendo questo <a href="../signup.html">link</a>.
                    </p>
                </aside>
            <?php endif; ?>
        </main>
    </body>
</html>
