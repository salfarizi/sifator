/**
 *
 * DatePickerControls
 *
 * Interface.Forms.Controls.DatePicker page content scripts. Initialized from scripts.js file.
 *
 *
 */

class DatePickerControls {
  constructor() {
    // Initialization of the page plugins
    if (!jQuery().datepicker) {
      console.log('datepicker is null!');
      return;
    }

    this._initBasic();
    this._initAutoClose();
    this._initOrientation();
    this._initValue();
    this._initFormat();
    this._initLocale();
    this._initInputGroup();
    this._initRange();
    this._initEmbed();
    this._initTopLabel();
    this._initFilled();
    this._initFloatingLabel();
  }

  _initBasic() {
    jQuery('#datePickerBasic').datepicker();
  }

  _initAutoClose() {
    jQuery('#datePickerAutoClose').datepicker({
      autoclose: true,
    });
  }

  _initOrientation() {
    jQuery('.date-picker-orientation').each(function () {
      jQuery(this).datepicker({
        orientation: jQuery(this).data('orientation'),
        autoclose: true,
      });
    });
  }

  _initValue() {
    jQuery('#datePickerValue').datepicker({
      autoclose: true,
    });
  }

  _initFormat() {
    jQuery('#datePickerFormat').datepicker({
      format: 'dd.mm.yyyy',
      autoclose: true,
    });
  }

  _initLocale() {
    jQuery('#datePickerLocale').datepicker({
      language: 'es',
      format: 'dd.mm.yyyy',
      autoclose: true,
    });
  }

  _initInputGroup() {
    jQuery('#datePickerInputGroup').datepicker({
      autoclose: true,
    });
  }

  _initRange() {
    jQuery('#laporan_pertanggal').datepicker({
      weekStart: 1,
      language: 'id',
      format: 'yyyy/mm/dd',
      autoclose: true,
    });
  }

  _initEmbed() {
    jQuery('#datePickerEmbed').datepicker();
  }

  _initTopLabel() {
    jQuery('#datePickerTopLabel').datepicker({
      autoclose: true,
    });
  }

  _initFilled() {
    jQuery('#datePickerFilled').datepicker({
      autoclose: true,
    });
  }

  _initFloatingLabel() {
    jQuery('#perpanjang_stnk').datepicker({
      language: 'id',
      format: 'yyyy/mm/dd',
      autoclose: true,
    });
  }

  _initFloatingLabel() {
    jQuery('#berlaku_sampai').datepicker({
      language: 'id',
      format: 'yyyy/mm/dd',
      autoclose: true,
    });
  }

  _initFloatingLabel() {
    jQuery('#tanggal_beli').datepicker({
      language: 'id',
      format: 'yyyy/mm/dd',
      autoclose: true,
    });
  }

  _initFloatingLabel() {
    jQuery('#tanggal_jual').datepicker({
      language: 'id',
      format: 'yyyy/mm/dd',
      autoclose: true,
    });
  }

  _initFloatingLabel() {
    jQuery('#tanggal_lahir').datepicker({
      language: 'id',
      format: 'yyyy/mm/dd',
      autoclose: true,
    });
  }

  _initFloatingLabel() {
    jQuery('#tanggal_perbaikan').datepicker({
      language: 'id',
      format: 'yyyy/mm/dd',
      autoclose: true,
    });
  }
  
}
