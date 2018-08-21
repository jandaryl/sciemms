<template>
  <Footer>
    <strong>Copyright &copy; {{ new Date().getFullYear() }} <a href="/" target="_blank">{{ name }}</a>.
    </strong>&nbsp;{{ $t('labels.all_rights_reserved') }}
  </Footer>
</template>
<script>
export default {
  name: 'AppFooter',
  props: {
    name: {
      type: String,
      default: ''
    },
    editorName: {
      type: String,
      default: ''
    },
    editorSiteUrl: {
      type: String,
      default: ''
    }
  }
}
</script>
