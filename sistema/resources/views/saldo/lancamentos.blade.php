@foreach( $lancamentos as $num => $val )
    @php
        $val = (array) $val;
    @endphp
    <tr>
        <td>{{ date('d-m-Y', strtotime($val['dtLancamento'])) }}</td>
        <td>{{ number_format($val['vlLancamento'],2,'.','') }}</td>
        <td>{{ $val['nmLancamento'] }}</td>
        <td>
            @if( $val['tpLancamento'] == '1' )
                <span class="text text-success"><i>Recebimento</i></span>
            @else
                <span class="text text-danger"><i>Pagamento</i></span>
            @endif
        </td>
    </tr>
@endforeach