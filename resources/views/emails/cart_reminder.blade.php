<h1>Olá {{ $userName }},</h1>

<p>Percebemos que você adicionou livros ao seu carrinho, mas ainda não finalizou a compra.</p>

<p>Itens no carrinho:</p>
<ul>
    @foreach($cartItems as $item)
        <li>{{ $item->book->name }} ({{ $item->quantity }})</li>
    @endforeach
</ul>

<p>Se precisar de ajuda, estamos à disposição!</p>

<a href="{{ url('/cart') }}">Ver meu carrinho</a>
