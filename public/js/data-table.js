(function($) {
  'use strict';
  $(function() {
    $('#order-listing').DataTable({
      "aLengthMenu": [[5, 10, 15, -1], [5, 10, 15, "الكل"]],
      "iDisplayLength": 10,
      "language": {
        "info": " عرض _START_ الى_END_ من _TOTAL_ السجلات",
        "infoEmpty":      " عرض 0 الى 0 من 0 السجلات",
          "zeroRecords":    "لاتوجد نتائج",
          "search": "بحث",
          "paginate": {
              "first":      "الاول",
              "last":       "الاخر",
              "next":       "التالي",
              "previous":   "السابق"
          },
          "lengthMenu": "عرض _MENU_ النتائج" 
      }
    });
    $('#order-listing').each(function(){
      var datatable = $(this);
      // SEARCH - Add the placeholder for Search and Turn this into in-line form control
      var search_input = datatable.closest('.dataTables_wrapper').find('div[id$=_filter] input');
      search_input.attr('placeholder', 'Search');
      search_input.removeClass('form-control-sm');
      // LENGTH - Inline-Form control
      var length_sel = datatable.closest('.dataTables_wrapper').find('div[id$=_length] select');
      length_sel.removeClass('form-control-sm');
    });
  });
})(jQuery);
