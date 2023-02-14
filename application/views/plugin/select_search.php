<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
  function search_select(module_name) {
    $('.search_select_' + module_name).select2({
      ajax: {
        url: '<?php echo site_url(); ?>generic_detail/search_keyword/' + module_name,
        dataType: 'json',
        delay: 250,
        data: function(params) {
          return {
            q: params.term, // search term
            page: params.page
          };
        },
        processResults: function(data, params) {
          // parse the results into the format expected by Select2
          // since we are using custom formatting functions we do not need to
          // alter the remote JSON data, except to indicate that infinite
          // scrolling can be used
          params.page = params.page || 1;

          return {
            results: data.items,
            pagination: {
              more: (params.page * 30) < data.total_count
            }
          };
        },
        cache: true
      },
      placeholder: 'Search here...',
      allowClear: true,
      minimumInputLength: 1,
    });
  }

  $(".select2-search__field").blur(function() {
    alert("This input field has lost its focus.");
  });

  function search_tag(module) {
    $(".search_" + module + "_name").select2({
      tags: true
    });
  }

  $(document).on('focus', '.select2-selection.select2-selection--single', function(e) {
    $(this).closest(".select2-container").siblings('select:enabled').select2('open');
  });

  // steal focus during close - only capture once and stop propogation
  $('select.select2').on('select2:closing', function(e) {
    $(e.target).data("select2").$selection.one('focus focusin', function(e) {
      e.stopPropagation();
    });
  });


  // $('select').on('select2:open', function() {
  //   alert('ddd');
  //   $("select").find('.select2-search__field').addClass('hidden');
  //   // $('.select2-search__field').prop('focus', false);
  // });


  $(document).ready(function() {
    $('.select_search').select2({
      placeholder: 'Select an option'
    });

    $(".select_search").select2().on('select2-focus', function() {
      $(this).select2('open');
    });

    $(".js-example-tokenizer").select2({
      tags: true,
      tokenSeparators: [',', ' ']
    })
  });
</script>