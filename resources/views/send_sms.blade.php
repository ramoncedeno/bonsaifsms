<form id="smsForm" action="{{ route('sms.send', ['phone' => 'PHONE_PLACEHOLDER', 'message' => 'MESSAGE_PLACEHOLDER']) }}" method="POST">
    @csrf
    <input type="text" name="phone" placeholder="phone" required>
    <input type="text" name="message" placeholder="message" required>
    <button type="submit">Enviar SMS</button>
</form>

<script>
    document.getElementById('smsForm').addEventListener('submit', function(event) {
        event.preventDefault();
        var phone = document.querySelector('input[name="phone"]').value;
        var message = document.querySelector('input[name="message"]').value;
        var action = this.action.replace('PHONE_PLACEHOLDER', encodeURIComponent(phone)).replace('MESSAGE_PLACEHOLDER', encodeURIComponent(message));
        this.action = action;
        this.submit();
    });
</script>
