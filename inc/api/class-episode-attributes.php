<?php
/** Episode Attributes
 *
 * @package KJR_Dev
 */

namespace KJR_Dev\Podcast_API\Api;

/** The Episode Attributes from Transistor */
class Episode_Attributes {
	/**
	 * The episode title
	 *
	 * @var string|null $title
	 */
	public ?string $title;

	/**
	 * The episode number
	 *
	 * @var int|null $number
	 */
	public ?int $number;

	/**
	 * The episode season
	 *
	 * @var int|null $season
	 */
	public ?int $season;

	/**
	 * The episode status
	 *
	 * @var string|null $status
	 */
	public ?string $status;

	/**
	 * The episode published date
	 *
	 * @var string|null $published_at
	 */
	public ?string $published_at;

	/**
	 * The episode duration
	 *
	 * @var int|null $duration
	 */
	public ?int $duration;

	/**
	 * The episode explicit status
	 *
	 * @var bool|null $explicit
	 */
	public ?bool $explicit;

	/**
	 * The episode keywords
	 *
	 * @var string|null $keywords
	 */
	public ?string $keywords;

	/**
	 * The episode alternate URL
	 *
	 * @var string|null $alternate_url
	 */
	public ?string $alternate_url;

	/**
	 * The episode media URL
	 *
	 * @var string|null $media_url
	 */
	public ?string $media_url;

	/**
	 * The episode image URL
	 *
	 * @var string|null $image_url
	 */
	public ?string $image_url;

	/**
	 * The episode video URL
	 *
	 * @var string|null $video_url
	 */
	public ?string $video_url;

	/**
	 * The episode author
	 *
	 * @var string|null $author
	 */
	public ?string $author;

	/**
	 * The episode summary
	 *
	 * @var string|null $summary
	 */
	public ?string $summary;

	/**
	 * The episode description
	 *
	 * @var string|null $description
	 */
	public ?string $description;

	/**
	 * The episode slug
	 *
	 * @var string|null $slug
	 */
	public ?string $slug;

	/**
	 * The episode created date
	 *
	 * @var string|null $created_at
	 */
	public ?string $created_at;

	/**
	 * The episode updated date
	 *
	 * @var string|null $updated_at
	 */
	public ?string $updated_at;

	/**
	 * The formatted episode published date
	 *
	 * @var string|null $formatted_published_at
	 */
	public ?string $formatted_published_at;

	/**
	 * The episode duration in mm:ss
	 *
	 * @var string|null $duration_in_mmss
	 */
	public ?string $duration_in_mmss;

	/**
	 * The episode share URL
	 *
	 * @var string|null $share_url
	 */
	public ?string $share_url;

	/**
	 * The episode transcript URL
	 *
	 * @var string|null $transcript_url
	 */
	public ?string $transcript_url;

	/**
	 * The formatted episode summary
	 *
	 * @var string|null $formatted_summary
	 */
	public ?string $formatted_summary;

	/**
	 * The formatted episode description
	 *
	 * @var string|null $formatted_description
	 */
	public ?string $formatted_description;

	/**
	 * The episode player embed HTML
	 *
	 * @var string|null $embed_html
	 */
	public ?string $embed_html;

	/**
	 * The dark-mode episode player embed HTML
	 *
	 * @var string|null $embed_html_dark
	 */
	public ?string $embed_html_dark;

	/**
	 * The episode audio processing status
	 *
	 * @var bool|null $audio_processing
	 */
	public ?bool $audio_processing;

	/**
	 * The episode type
	 *
	 * @var string|null $type
	 */
	public ?string $type;

	/**
	 * The episode email notifications
	 *
	 * @var string|null $email_notifications
	 */
	public ?string $email_notifications;

	/** Add array items to class props
	 *
	 * @param array $data the Episode attributes data
	 */
	public function __construct( array $data ) {
		$this->title                  = $data['title'] ?? null;
		$this->number                 = $data['number'] ?? null;
		$this->season                 = $data['season'] ?? null;
		$this->status                 = $data['status'] ?? null;
		$this->published_at           = $data['published_at'] ?? null;
		$this->duration               = $data['duration'] ?? null;
		$this->explicit               = $data['explicit'] ?? null;
		$this->keywords               = $data['keywords'] ?? null;
		$this->alternate_url          = $data['alternate_url'] ?? null;
		$this->media_url              = $data['media_url'] ?? null;
		$this->image_url              = $data['image_url'] ?? null;
		$this->video_url              = $data['video_url'] ?? null;
		$this->author                 = $data['author'] ?? null;
		$this->summary                = $data['summary'] ?? null;
		$this->description            = $data['description'] ?? null;
		$this->slug                   = $data['slug'] ?? null;
		$this->created_at             = $data['created_at'] ?? null;
		$this->updated_at             = $data['updated_at'] ?? null;
		$this->formatted_published_at = $data['formatted_published_at'] ?? null;
		$this->duration_in_mmss       = $data['duration_in_mmss'] ?? null;
		$this->share_url              = $data['share_url'] ?? null;
		$this->transcript_url         = $data['transcript_url'] ?? null;
		$this->formatted_summary      = $data['formatted_summary'] ?? null;
		$this->formatted_description  = $data['formatted_description'] ?? null;
		$this->embed_html             = $data['embed_html'] ?? null;
		$this->embed_html_dark        = $data['embed_html_dark'] ?? null;
		$this->audio_processing       = $data['audio_processing'] ?? null;
		$this->type                   = $data['type'] ?? null;
		$this->email_notifications    = $data['email_notifications'] ?? null;
	}
}
