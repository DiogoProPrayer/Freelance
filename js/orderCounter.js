function confirmCancel(orderId) {
    if (confirm("Are you sure you want to cancel order #" + orderId + "?")) {
        document.getElementById('cancel-order-form').submit();
    }
}


function updateOrderStatus() {
    fetch(`../api/get_order.php?order_id=${orderId}`)
        .then(response => response.json())
        .then(order => {
            document.getElementById('remaining-days').textContent = order.remaining_days;
            
            const statusElement = document.getElementById('order-status');
            statusElement.textContent = order.orderStatus;
            statusElement.className = order.orderStatus === 'Delayed' ? 'status-delayed' : 'status-ontime';
        });
}

// Atualizar a cada hora
setInterval(updateOrderStatus, 3600000);