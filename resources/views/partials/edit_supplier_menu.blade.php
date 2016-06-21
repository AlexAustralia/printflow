<div class="btn-group nav" >
    <a class="btn btn-primary" href="/suppliers/{{ $supplier->id }}/edit">Supplier Contacts</a>
    <a class="btn btn-primary" href="/suppliers/{{ $supplier->id }}/review">Supplier Review</a>
    @if(Auth::check())
        @if(Auth::user()->admin == 1)
            <a class="btn btn-warning" href="/suppliers/{{ $supplier->id }}/access">Edit Access to Review</a>
        @endif
    @endif
</div>
<p style="clear:both; margin-bottom:40px;"></p>