// Sélectionnez le champ "zone" et le champ "quantité" par leur ID
var zoneInput = document.getElementById("zone");
var quantiteInput = document.getElementById("quantite");

// Ajoutez un écouteur d'événement pour le champ "zone"
zoneInput.addEventListener("input", function() {
    // Récupérez la valeur du champ "zone"
    var zoneValue = parseFloat(zoneInput.value);

    // Vérifiez si la valeur de "zone" est un nombre valide
    if (!isNaN(zoneValue)) {
        // Effectuez le calcul et mettez à jour la valeur du champ "quantité"
        var quantiteValue = Math.ceil(zoneValue / 42);
        quantiteInput.value = quantiteValue;
    } else {
        // Si la valeur de "zone" n'est pas un nombre valide, videz le champ "quantité"
        quantiteInput.value = "";
    }
});