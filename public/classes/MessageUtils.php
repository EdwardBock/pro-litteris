<?php


namespace Palasthotel\ProLitteris;


class MessageUtils {

	const PARTICIPATIONS = [ "AUTHOR", "TRANSLATOR", "IMAGE_ORIGINATOR" ];

	/**
	 * @param string $memberId
	 * @param string $internalIdentification
	 * @param string $participation AUTHOR || TRANSLATOR || IMAGE_ORIGINATOR
	 * @param null|string $firstName
	 * @param null|string $surName
	 *
	 * @return array
	 */
	public static function buildParticipant( $memberId, $internalIdentification, $participation, $firstName, $surName ) {
		$participation = ( in_array( $participation, self::PARTICIPATIONS ) ) ? $participation : self::PARTICIPATIONS[0];

		return array(
			"memberId"               => $memberId,
			"internalIdentification" => $internalIdentification,
			"participation"          => $participation,
			"firstName"              => $firstName,
			"surName"                => $surName
		);
	}

	/**
	 * @param string $title
	 * @param string $plainTextBase64Encoded
	 * @param array $participants array with items build with buildParticipant
	 * @param string $pixelUid
	 *
	 * @return array
	 */
	public static function buildMessage( $title, $plainTextBase64Encoded, $participants, $pixelUid ) {
		return array(
			"title"        => $title,
			"messageText"  => array(
				"plainText" => $plainTextBase64Encoded,
			),
			"participants" => $participants,
			"pixelUid"     => $pixelUid
		);
	}

	/**
	 * @param array $message
	 *
	 * @return bool
	 */
	public static function isMessageValid( $message ) {

		if ( ! is_array( $message ) ) {
			return false;
		}

		if ( ! isset( $message["title"] ) || empty( $message["title"] ) ) {
			return false;
		}

		if (
			! isset( $message["messageText"] ) || ! is_array( $message["messageText"] )
			||
			! isset( $message["messageText"]["plainText"] ) || empty( $message["messageText"]["plainText"] )
			||
			strlen( base64_decode( $message["messageText"]["plainText"] ) ) < Options::getMinCharCount()
		) {
			return false;
		}

		if ( ! isset( $message["pixelUid"] ) || empty( $message["pixelUid"] ) ) {
			return false;
		}

		if ( ! isset( $message["participants"] ) || ! is_array( $message["participants"] ) || count( $message["participants"] ) == 0 ) {
			return false;
		}

		foreach ( $message["participants"] as $participant ) {
			if ( ! self::isParticipantValid( $participant ) ) {
				return false;
			}
		}

		return true;
	}

	/**
	 * @param $participant
	 *
	 * @return bool
	 */
	public static function isParticipantValid( $participant ) {

		if ( ! isset( $participant["memberId"] ) || empty( $participant["memberId"] ) ) {
			return false;
		}
		if ( ! isset( $participant["internalIdentification"] ) || empty( $participant["internalIdentification"] ) ) {
			return false;
		}
		if (
			! isset( $participant["participation"] )
			||
			( ! in_array( $participant["participation"], self::PARTICIPATIONS ) )
		) {
			return false;
		}

		if ( ! isset( $participant["firstName"] ) || empty( $participant["firstName"] ) ) {
			return false;
		}
		if ( ! isset( $participant["surName"] ) || empty( $participant["surName"] ) ) {
			return false;
		}

		return true;
	}

}
