@extends('layouts.navBare')

@section('content')
<div class="container py-4 border border-warning">
    <h2 class="mb-4 text-center"><i class="bi bi-robot"></i> Assistant cuisine IA</h2>

    <div id="chatbox" class="border p-3 mb-3 rounded" style="height: 400px; overflow-y: auto; background: #fff;">
        <!-- Messages ajoutés ici -->
    </div>

    <div class="input-group">
        <input type="text" id="userInput" class="form-control" placeholder="Pose ta question de cuisine...">
        <button class="btn btn-danger" id="sendMessageBtn">send</button>
    </div>
</div>

<script>
    const chatbox = document.getElementById('chatbox');
    const input = document.getElementById('userInput');
    const sendBtn = document.getElementById('sendMessageBtn');

    sendBtn.addEventListener('click', sendMessage);
    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') sendMessage();
    });

    async function sendMessage() {
        const message = input.value.trim();
        if (!message) return;

        // Ajoute le message de l'utilisateur à droite
        chatbox.innerHTML += `
            <div class="text-end mb-2">
               
                <span class="p-2 d-inline-block bg-primary text-white rounded" style="max-width: 70%;">${message}</span>
            </div>`;
        chatbox.scrollTop = chatbox.scrollHeight;
        input.value = '';

        // Envoie à l’API Laravel
        const response = await fetch('/chatbot', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    },
    body: JSON.stringify({ message })
});


        const data = await response.json();

        // Ajoute la réponse de l’IA à gauche
        chatbox.innerHTML += `
            <div class="text-start mb-2">
                <span class="p-2 d-inline-block bg-light border rounded" style="max-width: 70%;">${data.reply}</span>
            </div>`;
        chatbox.scrollTop = chatbox.scrollHeight;
    }
</script>
@endsection
