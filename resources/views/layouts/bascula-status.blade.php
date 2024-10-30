<div class="bascula-status float-right">
    <!-- Estado Operativa -->
    @if ($bascula->operativa)
        <div class="status-container text-success">
            <div class="icon-container">
                <i class="bi bi-arrow-up-circle-fill"></i>
            </div>
            <span class="status-text">Operativa</span>
        </div>
    @else
        <div class="status-container text-danger">
            <div class="icon-container">
                <i class="bi bi-arrow-down-circle-fill"></i>
            </div>
            <span class="status-text">En Desuso</span>
        </div>
    @endif

    <!-- Datos Completos -->
    @if ($bascula->datos_completos)
        <div class="status-container text-success">
            <div class="icon-container">
                <i class="bi bi-file-earmark-check-fill"></i>
            </div>
            <span class="status-text">Datos Completos</span>
        </div>
    @else
        <div class="status-container text-danger">
            <div class="icon-container">
                <i class="bi bi-file-earmark-x-fill"></i>
            </div>
            <span class="status-text">Datos Incompletos</span>
        </div>
    @endif
</div>
