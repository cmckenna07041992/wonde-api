export function useCommon() {

  function formatAddress(addressObj) {
    return addressObj.address_line_1 + ' ' +
                          addressObj.address_line_2 + ' ' +
                          addressObj.address_town + ' ' +
                          addressObj.address_postcode + ' ' +
                          addressObj.address_country.name + ' (' + addressObj.address_country.code + ')';
  }

  function formatName(title, firstName, lastName) {
    return title + ' ' + firstName + ' ' + lastName;
  }

  return { formatAddress, formatName };
}