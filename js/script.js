document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('travelForm');
    
    form.addEventListener('submit', function (event) {
        // Ici, vous pouvez accéder aux éléments de formulaire
        const camping = document.getElementById('camping').value;
        const dateDebut = document.getElementById('departure').value;
        const dateFin = document.getElementById('end').value;
        const nombrePersonnes = document.getElementById('people').value;

        // Logique supplémentaire ici
        console.log(`Camping: ${camping}, Date début: ${dateDebut}, Date fin: ${dateFin}, Nombre de personnes: ${nombrePersonnes}`);
    });
});
