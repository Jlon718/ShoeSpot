$(document).ready(function () {
    document.addEventListener('DOMContentLoaded', function () {
        fetchOrders();
    });
    
    function fetchOrders() {
        fetch('/api/orders')
            .then(response => response.json())
            .then(data => {
                displayOrders(data);
            })
            .catch(error => {
                console.error('Error fetching orders:', error);
            });
    }
    
    function displayOrders(orders) {
        const tableBody = document.querySelector('#orders-table tbody');
        tableBody.innerHTML = ''; // Clear existing rows
    
        if (orders.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="4">You haven\'t purchased any products yet.</td></tr>';
        } else {
            orders.forEach(order => {
                order.products.forEach(product => {
                    const row = document.createElement('tr');
                    
                    const productNameCell = document.createElement('td');
                    productNameCell.textContent = product.product_name;
                    row.appendChild(productNameCell);
    
                    const priceCell = document.createElement('td');
                    priceCell.textContent = `₱${product.sell_price}`;
                    row.appendChild(priceCell);
    
                    const statusCell = document.createElement('td');
                    statusCell.textContent = getStatusText(order.status);
                    row.appendChild(statusCell);
    
                    const viewProductCell = document.createElement('td');
                    const viewProductButton = document.createElement('a');
                    viewProductButton.href = `/products/info/${product.product_id}`;
                    viewProductButton.className = 'btn btn-primary';
                    viewProductButton.textContent = 'View Product';
                    viewProductCell.appendChild(viewProductButton);
                    row.appendChild(viewProductCell);
    
                    tableBody.appendChild(row);
                });
            });
        }
    }
    
    function getStatusText(status) {
        switch (status) {
            case 0:
                return 'Pending';
            case 1:
                return 'Shipped';
            case 2:
                return 'Delivered';
            case 3:
                return 'Cancelled';
            default:
                return 'Unknown Status';
        }
    }
});
