/**
 *
 * FormLayouts
 *
 * Interface.Forms.Layouts page content scripts. Initialized from scripts.js file.
 *
 *
 */

class FormLayouts {
  constructor() {
    // Initialization of the page plugins

    this._initBasicForm();
    this._initTopLabelForm();
    this._initFloatingLabelForm();
    this._initFilledForm();
  }

  // Basic form initialization
  _initBasicForm() {
    if (jQuery().select2) {
      jQuery('#selectBasic').select2({minimumResultsForSearch: Infinity, placeholder: ''});
    }
    if (jQuery().select2) {
      jQuery('#roles').select2({minimumResultsForSearch: Infinity, placeholder: ''});
    }
    if (typeof Tagify !== 'undefined') {
      if (document.querySelector('#tagBasic') !== null) {
        new Tagify(document.querySelector('#tagBasic'));
      }
    }
    if (jQuery().datepicker) {
      jQuery('#datePickerBasic').datepicker({
        autoclose: true,
      });
    }
  }

  // Top label form initialization
  _initTopLabelForm() {
    if (jQuery().select2) {
      jQuery('#select2TopLabel').select2({minimumResultsForSearch: Infinity, placeholder: ''});
    }
    if (typeof Tagify !== 'undefined') {
      if (document.querySelector('#tagsTopLabel') !== null) {
        new Tagify(document.querySelector('#tagsTopLabel'));
      }
    }
    if (jQuery().datepicker) {
      jQuery('#dateTopLabel').datepicker({
        autoclose: true,
      });
    }
  }

  // Floating label form initialization
  _initFloatingLabelForm() {
    const _this = this;
    if (jQuery().select2) {
      jQuery('#penjual')
        .select2({minimumResultsForSearch: Infinity, placeholder: ''})
        .on('select2:open', function (e) {
          jQuery(this).addClass('show');
        })
        .on('select2:close', function (e) {
          _this._addFullClassToSelect2(this);
          jQuery(this).removeClass('show');
        });
      this._addFullClassToSelect2(jQuery('#penjual'));
    }
    if (jQuery().select2) {
      jQuery('#jenis_pembayaran')
        .select2({minimumResultsForSearch: Infinity, placeholder: ''})
        .on('select2:open', function (e) {
          jQuery(this).addClass('show');
        })
        .on('select2:close', function (e) {
          _this._addFullClassToSelect2(this);
          jQuery(this).removeClass('show');
        });
      this._addFullClassToSelect2(jQuery('#jenis_pembayaran'));
    }
    if (jQuery().select2) {
      jQuery('#jenis_kelamin')
        .select2({minimumResultsForSearch: Infinity, placeholder: ''})
        .on('select2:open', function (e) {
          jQuery(this).addClass('show');
        })
        .on('select2:close', function (e) {
          _this._addFullClassToSelect2(this);
          jQuery(this).removeClass('show');
        });
      this._addFullClassToSelect2(jQuery('#jenis_kelamin'));
    }
    if (jQuery().select2) {
      jQuery('#kredit_via_leasing')
        .select2({minimumResultsForSearch: Infinity, placeholder: ''})
        .on('select2:open', function (e) {
          jQuery(this).addClass('show');
        })
        .on('select2:close', function (e) {
          _this._addFullClassToSelect2(this);
          jQuery(this).removeClass('show');
        });
      this._addFullClassToSelect2(jQuery('#kredit_via_leasing'));
    }
    if (jQuery().select2) {
      jQuery('#jenis_transaksi')
        .select2({minimumResultsForSearch: Infinity, placeholder: ''})
        .on('select2:open', function (e) {
          jQuery(this).addClass('show');
        })
        .on('select2:close', function (e) {
          _this._addFullClassToSelect2(this);
          jQuery(this).removeClass('show');
        });
      this._addFullClassToSelect2(jQuery('#jenis_transaksi'));
    }
    if (typeof Tagify !== 'undefined') {
      if (document.querySelector('#tagsFloatingLabel') !== null) {
        new Tagify(document.querySelector('#tagsFloatingLabel'));
      }
    }
    if (jQuery().datepicker) {
      jQuery('#berlaku_sampai')
        .datepicker({
          language: 'id',
          format: 'yyyy/mm/dd',
          autoclose: true,
        })
        .on('show', function (e) {
          jQuery(this).addClass('show');
        });

        jQuery('#perpanjang_stnk')
        .datepicker({
          language: 'id',
          format: 'yyyy/mm/dd',
          autoclose: true,
        })
        .on('show', function (e) {
          jQuery(this).addClass('show');
        });

        jQuery('#tanggal_beli')
        .datepicker({
          language: 'id',
          format: 'yyyy/mm/dd',
          autoclose: true,
        })
        .on('show', function (e) {
          jQuery(this).addClass('show');
        });

        jQuery('#tanggal_jual')
        .datepicker({
          language: 'id',
          format: 'yyyy/mm/dd',
          autoclose: true,
        })
        .on('show', function (e) {
          jQuery(this).addClass('show');
        });

        jQuery('#tanggal_lahir')
        .datepicker({
          language: 'id',
          format: 'yyyy/mm/dd',
          autoclose: true,
        })
        .on('show', function (e) {
          jQuery(this).addClass('show');
        });
    }
  }

  // Filled form initialization
  _initFilledForm() {
    if (jQuery().select2) {
      jQuery('#select2Filled').select2({minimumResultsForSearch: Infinity});
    }
    if (typeof Tagify !== 'undefined') {
      if (document.querySelector('#tagsFilled') !== null) {
        new Tagify(document.querySelector('#tagsFilled'));
      }
    }
    if (jQuery().datepicker) {
      jQuery('#datePickerFilled').datepicker({
        autoclose: true,
      });
    }
  }

  // Helper method for floating label Select2
  _addFullClassToSelect2(el) {
    if (jQuery(el).val() !== '' && jQuery(el).val() !== null) {
      jQuery(el).parent().find('.select2.select2-container').addClass('full');
    } else {
      jQuery(el).parent().find('.select2.select2-container').removeClass('full');
    }
  }
}
