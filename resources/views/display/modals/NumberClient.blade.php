<div class="modal fade" id="client-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="margin-top: 20%">
        <div class="modal-content" v-if="ticket.has_number">
            
            <div class="modal-header">
                <h5 class="modal-title">¿Cuenta con un número de cliente?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-9">
                        <input class="form-control form-control-lg text-center" id="client" type="text" v-model="ticket.client_number">
                    </div>   
                    
                    <div class="col-3">
                        <button class="btn btn-success btn-lg btn-block" type="button" @click="verifyClientNumber()"><i class="fas fa-check"></i></button>
                    </div>
                </div>
            </div>
            <div class="modal-footer" style="margin: auto">
                <button class="btn btn-secondary" type="button" data-toggle="modal" data-target="#sex-moal" v-on:click="ticket.has_number=false">NO. Continuar</button>
            </div>


        </div>

        <div class="modal-content" v-else>
            <div class="modal-header">
                <h5 class="modal-title">¿Cuál es tu sexo?</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col">
                        <button class="btn btn-success btn-lg btn-block" type="button" v-on:click="setSex('F')">
                            <p><i class="fas fa-female fa-3x"></i></p>
                            FEMENINO
                        </button>
                    </div>
                    <div class="col">
                        <button class="btn btn-success btn-lg btn-block" type="button" v-on:click="setSex('M')">
                            <p><i class="fas fa-male fa-3x"></i></p>
                            MASCULINO
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>