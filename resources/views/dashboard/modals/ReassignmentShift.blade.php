<div class="modal fade" id="reassignment-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="margin-top: 17%">
        <div class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title">Reasignar turno</h5>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form method="POST" v-on:submit.prevent="reassignmentShift" id="form-advisor">
                    @csrf
                    <input type="hidden" value="{{ Auth::id() }}" name="send_id">
                    <div class="row">
                        <div class="col-9">
                            <div class="form-group">
                                <select class="form-control" id="exampleSelect1" name="recive_id">
                                    <option value="0">Selecciona un supervisor</option>
                                    <option :value="adviser.user" v-for="adviser in advisers">${ adviser.name } ${ adviser.first_name } ${ adviser.second_name }</option>
                                </select>
                            </div>
                        </div>   
                    <input type="hidden" value="" name="shift_id" id="shift_id">
                        
                        <div class="col-3">
                            <button class="btn btn-success btn-block" type="submit" ><i class="fas fa-check"></i></button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>