<?php

namespace Devidw\MarsRoverPhoto;

use Devidw\MarsRoverPhoto\Rover;

/**
 * PHP Wrapper for NASA's Mars Rover Photos API
 * 
 * @see https://github.com/chrisccerami/mars-photo-api
 * @see https://api.nasa.gov
 */
class MarsRoverPhoto
{
  /**
   * Rovers
   * 
   * @var array
   */
  private $rovers;

  /**
   * Rover cameras
   * 
   * @var array
   */
  private $cameras;

  /**
   * API Base URL
   * 
   * @var string
   */
  private $baseURL;

  /**
   * API Query Parameters
   * 
   * @var array
   */
  private $params;

  /**
   * Constructor
   * 
   * @param string $apiKey
   * @param string $rover
   */
  function __construct(
    private string $apiKey,
    public string $rover
  ) {
    $this->rovers = [
      'curiosity' => new Rover(
        cameras: ['FHAZ', 'RHAZ', 'MAST', 'CHEMCAM', 'MAHLI', 'MARDI', 'NAVCAM']
      ),
      'opportunity' => new Rover(
        cameras: ['FHAZ', 'RHAZ', 'NAVCAM', 'PANCAM', 'MINITES']
      ),
      'spirit' => new Rover(
        cameras: ['FHAZ', 'RHAZ', 'NAVCAM', 'PANCAM', 'MINITES']
      ),
    ];

    // validate rover input
    if (!array_key_exists($this->rover, $this->rovers)) {
      $rovers = implode(', ', array_keys($this->rovers));
      throw new Exception("rover must be one of $rovers");
    }

    $this->baseURL = "https://api.nasa.gov/mars-photos/api/v1/rovers/{$this->rover}/photos";
    $this->params = [
      'api_key' => $this->apiKey,
    ];
    $this->cameras = $this->rovers[$this->rover]->cameras;
  }

  /**
   * Get the requested photos
   * 
   * @return object
   */
  public function get(): object
  {
    $query = http_build_query($this->params);
    $request = "$this->baseURL?$query";
    $response = file_get_contents($request);
    $response = json_decode($response);
    return $response;
  }

  /**
   * Set the sol to filter by th photo's sol
   * 
   * @param int $sol
   * @return MarsRoverPhoto
   */
  public function sol(int $sol)
  {
    $this->params['sol'] = $sol;
    return $this;
  }

  /**
   * Set the earth's date to filter by the photo's earth date
   * 
   * @param string $earthDate
   * @return MarsRoverPhoto
   */
  public function earthDate(string $earthDate)
  {
    $this->params['earth_date'] = $earthDate;
    return $this;
  }

  /**
   * Set the camera to filter by the photo's camera
   * 
   * @param string $camera
   * @return MarsRoverPhoto
   */
  public function camera(string $camera)
  {
    // validate camera input
    if (!in_array($camera, $this->cameras)) {
      $cameras = implode(', ', $this->cameras);
      throw new Exception("camera must be one of $cameras");
    }
    $this->params['camera'] = $camera;
    return $this;
  }

  /**
   * Set the page to retrieve a specific page of results
   * 
   * @param int $page
   * @return MarsRoverPhoto
   */
  public function page(int $page)
  {
    $this->params['page'] = $page;
    return $this;
  }
}
