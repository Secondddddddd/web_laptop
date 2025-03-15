<div id="{{$idName}}"></div>

<script type="module">

    const grid = new Grid({
        columns: [
            'productCode',
            'productName',
            'productLine',
            'productScale',
            'productVendor',
            'productDescription',
            {
                name: 'quantityInStock',
                formatter: (cell) => html(`<span class="badge">${cell}</span>`)
            },
            'buyPrice',
            'MSRP'
        ],
        pagination: true,
        search: true,
        data: @json($data),
        style:{
            td: {
                'text-align': 'center'
            }
        }
    }).render(document.getElementById('{{$idName}}'));

</script>
