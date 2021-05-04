<div class="ticket">
    <div class="ticket-content" id="ticket" style="font-family: 'Lato', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif">
        <p style="text-align: center">
            <img src="{{ asset('img/madero-logo.jpeg') }}" alt="" width="200px" style="margin: auto;">
        </p>
        <div style="text-align: center; margin-bottom: 30px;">
            Bienvenido, usted será atendido con el turno:
        </div>
        
        <div style="font-size: 70px; text-align: center; line-height:1;" id="shift"></div>
        <div id="speciality" style="text-align: center; font-size: 25px; margin-top:15px;"></div>  

        <div style="display: flex;flex-wrap:wrap;margin-right:-15px;margin-left:-15px; margin-top:35px">
            <div style="flex: 0 0 50%;max-width: 50%;position: relative;text-align: center !important;">${ office.date }</div>
            <div id="hours" style="flex: 0 0 50%;max-width: 50%;position: relative;text-align: center;"></div>
        </div>
    </div>
</div>