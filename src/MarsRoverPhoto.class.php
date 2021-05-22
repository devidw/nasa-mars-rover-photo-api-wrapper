<?php

/**
 * @class MarsRoverPhoto
 *
 * PHP Wrapper for NASAs Mars Rover Photos API
 * @link https://github.com/chrisccerami/mars-photo-api
 * @link https://api.nasa.gov
 *
 * @author David Wolf <david@wolf.gdn>
 * @website http://david.wolf.gdn
 */
class MarsRoverPhoto
{
  private $rovers;
  private $cameras;
  private $baseURL;
  private $params;

  /**
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

    $this->baseURL = "https://api.nasa.gov/mars-photos/api/v1/rovers/$this->rover/photos";
    $this->params = [
      'api_key' => $this->apiKey,
    ];
    $this->cameras = $this->rovers[$this->rover]->cameras;
  }

  /**
   * @return object
   */
   public function get(): object
  {
    $query = http_build_query($this->params);
    $request = "$this->baseURL?$query";
    // return $request;
    $response = file_get_contents($request);
    $response = json_decode($response);
    return $response;
  }

  /**
   * @param int $sol
   * @return MarsRoverPhoto
   */
  public function sol(int $sol)
  {
    $this->params['sol'] = $sol;
    return $this;
  }

  /**
   * @param string $earthDate
   * @return MarsRoverPhoto
   */
  public function earthDate(string $earthDate)
  {
    $this->params['earth_date'] = $earthDate;
    return $this;
  }

  /**
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
   * @param int $page
   * @return MarsRoverPhoto
   */
  public function page(int $page)
  {
    $this->params['page'] = $page;
    return $this;
  }
}
