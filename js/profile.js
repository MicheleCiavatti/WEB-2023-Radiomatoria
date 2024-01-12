function modificaProfilo(nome_utente) {
    const alter_form = document.getElementsByName('alter_form');
    if(nome_utente == readCookie('NomeUtente')) {
        if(alter_form.hidden == true) {
            alter_form.hidden = false;
        } else {
            alter_form.hidden = true;
        }
    }
}

const riga1 = document.getElementById("riga_orari_mattina");
const riga2 = document.getElementById("riga_orari_sera");

function tabellaOrari(oraInizio, oraFine) {
    for(let i=oraInizio; i<oraFine; i++) {
        if(i<=12) {
            riga1.children.item(i).style.background = "green";
        } else {
            riga2.children.item(i-12).style.background = "green";
        }
    }
}

function oraCorrente() {
    const intestazione = document.getElementById("intestazione_orari");
    let tempo_corrente = new Date();
    let ora_corrente = tempo_corrente.getHours();
    if(ora_corrente<=12) {
        intestazione.children.item(ora_corrente.getHours()).style.color = "green";
        riga1.children.item(0).style.color = "green";
    } else {
        intestazione.children.item(ora_corrente.getHours()-12).style.color = "green";
        riga2.children.item(0).style.color = "green";
    }
}