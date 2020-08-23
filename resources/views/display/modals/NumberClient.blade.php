<div class="modal fade" id="client-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Cuenta con un número de cliente?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <input class="form-control form-control-lg" id="inputLarge" type="text" v-model="ticket.client_number">
                    </div>        
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-success" type="button" @click="verifyClientNumber()">Listo</button>
                <button class="btn btn-success" type="button" data-toggle="modal" data-target="#sex-modal">NO</button>
            </div>
        </div>
    </div>
</div>