
@if( Session::get('message') != "" )
    @if( Session::get('tipoMessage') == 1)
        <script>
            /**
             * @author: Fernando Bino Machado
             * fonte: public/js/geral.js
             * funcao: message()
            */
            var msg = "{{Session::get('message')}}"
            message(msg,1)
        </script>
    @elseif( Session::get('tipoMessage') == 2)
        <script>
            /**
             * @author: Fernando Bino Machado
             * fonte: public/js/geral.js
             * funcao: message()
            */
            var msg = "{{Session::get('message')}}"
            message(msg,2)
        </script>
    @else
        <script>
            /**
             * @author: Fernando Bino Machado
             * fonte: public/js/geral.js
             * funcao: message()
            */
            var msg = "{{Session::get('message')}}"
            message(msg,3)
        </script>
    @endif
    
    @php
        session(['message'=>''])
    @endphp
@endif