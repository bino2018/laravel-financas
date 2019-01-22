@foreach( $lancamentos as $num => $val )
    @php
        $val = (array) $val;
    @endphp

    <div class="card card-lancamentos">
        <div class="card card-header">
            <h6 class="text-card">{{ $val['nmLancamento'] }}</h6>
        </div>
        <div class="card card-body">
            <table class="table table-sm">
                <tr>
                    <td>Data</td>
                    <td>Valor</td>
                    <td>Tipo</td>
                </tr>
                <tr>
                    <td>{{ date('d-m-Y', strtotime($val['dtLancamento'])) }}</td>
                    <td>{{ number_format($val['vlLancamento'],2,'.','') }}</td>
                    <td>
                        @if( $val['tpLancamento'] == '1' )
                            <span class="text text-success"><i>Recebimento</i></span>
                        @else
                            <span class="text text-danger"><i>Pagamento</i></span>
                        @endif
                    </td>
                </tr>

            </table>
        </div>
        
    </div>

@endforeach