document.getElementById('travelForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const destination = document.getElementById('destination').value;
    const departure = document.getElementById('departure').value;
    const people = document.getElementById('people').value;

    const resultDiv = document.getElementById('result');
    resultDiv.innerHTML = `<p>Vous allez à <strong>${destination}</strong>.</p>
                           <p>Vous partez le <strong>${departure}</strong>.</p>
                           <p>Vous serez <strong>${people}</strong> personnes.</p>`;

    resultDiv.style.display = 'block';

    setTimeout(() => {
        window.location.href = "reservation.html"; 
    }, 100);
});
