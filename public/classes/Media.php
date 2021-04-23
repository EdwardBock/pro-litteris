<?php


namespace Palasthotel\ProLitteris;

use Palasthotel\WordPress\Attachment\SelectMetaField;
use Palasthotel\WordPress\Model\Option;
use WP_User;

class Media extends _Component {

	/**
	 * @var SelectMetaField
	 */
	private $attachment_author_field;

	public function onCreate() {
		$this->attachment_author_field = SelectMetaField::build( Plugin::ATTACHMENT_META_AUTHOR )
		                                                ->label( "Pro-Litteris" )
		                                                ->help( "Bildautor für die Meldung an Pro-Litteris." );
		add_action( 'admin_init', [ $this, 'init' ] );
	}

	public function init() {
		$authors = get_users( [
			"who" => "authors",
		] );

		$options = array_map( function ( $author ) {
			/**
			 * @var WP_User $author
			 */
			$id      = $this->plugin->user->getProLitterisId( $author->ID );
			$surName = $this->plugin->user->getProLitterisSurname( $author->ID );
			$name    = $this->plugin->user->getProLitterisName( $author->ID );

			$displayName = ( ! empty( $surName ) && ! empty( $name ) ) ? "$surName $name" : $author->display_name;

			return Option::build( $author->ID, "{$displayName} ($id)" );
		}, $authors );

		$this->attachment_author_field->options(
			array_merge( [ Option::build( "", "" ) ], $options )
		);
	}

	public function getAuthor( $attachment_id ) {
		return $this->attachment_author_field->getValue( $attachment_id );
	}

}