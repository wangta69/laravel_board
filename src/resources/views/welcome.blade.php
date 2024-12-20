
<x-bbs::default
  header="pondol-common::bare.header"
  footer="pondol-common::bare.footer"
>
  <div class="container">
    <div class="row mt-5">
      @foreach($items as $v) 
      <x-bbs-card :table="$v" cnt="5" />
      @endforeach
    </div>
  </div>
</x-bbs>
