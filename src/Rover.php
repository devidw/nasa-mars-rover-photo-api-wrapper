<?php

namespace Devidw\MarsRoverPhoto;

/**
 * Rover class for Mars Rover Photos API Wrapper
 */
class Rover
{

  /**
   * Constructor
   * 
   * @param array $cameras The rover's cameras
   */
  function __construct(
    public array $cameras
  ) {
  }
}
