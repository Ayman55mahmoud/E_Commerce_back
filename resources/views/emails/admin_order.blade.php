<h2>New Order Created</h2>

<p>Order ID: {{ $order->id }}</p>

<p>User: {{ $order->user->name }}</p>

<p>Email: {{ $order->user->email }}</p>

<p>Total Price: {{ $order->total_price }}</p>