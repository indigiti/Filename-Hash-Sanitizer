/**
 * Produces cleaner filenames for uploads
 *
 * @param  string $filename
 * @return string
 */
function wpartisan_sanitize_file_name( $filename ) {
 
    $sanitized_filename = remove_accents( $filename ); // Convert to ASCII
 
    // Standard replacements
    $invalid = array(
        ' '   => '-',
        '%20' => '-',
        '_'   => '-',
    );
    $sanitized_filename = str_replace( array_keys( $invalid ), array_values( $invalid ), $sanitized_filename );
 
    $sanitized_filename = preg_replace('/[^A-Za-z0-9-\. ]/', '', $sanitized_filename); // Remove all non-alphanumeric except .
    $sanitized_filename = preg_replace('/\.(?=.*\.)/', '', $sanitized_filename); // Remove all but last .
    $sanitized_filename = preg_replace('/-+/', '-', $sanitized_filename); // Replace any more than one - in a row
    $sanitized_filename = str_replace('-.', '.', $sanitized_filename); // Remove last - if at the end
    $sanitized_filename = strtolower( $sanitized_filename ); // Lowercase
 
    return $sanitized_filename;
}
 
add_filter( 'sanitize_file_name', 'wpartisan_sanitize_file_name', 10, 1 );

function make_filename_hash($filename) {

    if( isset($_REQUEST['post_id']) ) {
        $post_id =  (int)$_REQUEST['post_id'];
    }else{
        $post_id=0;
    }

    $info = pathinfo($filename);
    $ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
    $name = basename($filename, $ext);

    if($post_id>0){
        return $post_id."_".$name . $ext;
    }else{
        return $name . $ext;
    }
}
add_filter('sanitize_file_name', 'make_filename_hash', 10);
