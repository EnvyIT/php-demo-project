/* Material.js is setting on page load the state of an input field to invalid
*  even if the user has not entered something yet.
*  So we override the checkValidity method and set a custom class removing theirs
*  on the first load and reactivating validation after that.
*/
MaterialTextfield.prototype.checkValidity = function () {
  let CLASS_VALIDITY_INIT = "validity-init";
  if (this.input_ && this.input_.validity && this.input_.validity.valid) {
    this.element_.classList.remove(this.CssClasses_.IS_INVALID);
  } else {
    if (this.input_ && this.input_.value.length > 0) {
      this.element_.classList.add(this.CssClasses_.IS_INVALID);
    } else if (this.input_ && this.input_.value.length === 0) {
      if (this.input_.classList.contains(CLASS_VALIDITY_INIT)) {
        this.element_.classList.add(this.CssClasses_.IS_INVALID);
      }
    }
  }
  if (!this.input_.length && !this.input_.classList.contains(
      CLASS_VALIDITY_INIT)) {
    this.input_.classList.add(CLASS_VALIDITY_INIT);
  }
};
