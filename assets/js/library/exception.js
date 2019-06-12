/**
 * Kostum Error Exception untuk membuat warning error (bukan fatal error).
 * 
 * @param {string} message Pesan yang ingin dimasukkan kedalam error.
 * @param {object} metadata Data Object yang ingin disertakan kedalam error (opsional).
 * @returns {Error}
 */
function InfoException(message, metadata = {}) {
    const error = new Error(message);
    error.code = "InfoException";
    error.metadata = metadata;
    return error;
}

InfoException.prototype = Object.create(Error.prototype);