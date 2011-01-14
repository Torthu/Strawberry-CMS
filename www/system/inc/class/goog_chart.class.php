<?php

#
# Chart
#
# by Ludwig Pettersson
# <http://luddep.se>
#
# With help from Fredrik Holmstrцm
# <http://loveandtheft.org/>
#

# Copyright (c) 2008 Ludwig Pettersson

# Permission is hereby granted, free of charge, to any person obtaining a copy
# of this software and associated documentation files (the "Software"), to deal
# in the Software without restriction, including without limitation the rights
# to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
# copies of the Software, and to permit persons to whom the Software is
# furnished to do so, subject to the following conditions:

# The above copyright notice and this permission notice shall be included in
# all copies or substantial portions of the Software.

# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
# IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
# FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
# AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
# LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
# OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
# THE SOFTWARE.

class GoogChart
{
	// Constants
	const BASE = 'http://chart.apis.google.com/chart?';

	// Variables
	protected $types = array(
							'pie' => 'p',
							'pie3' => 'p3',
							'line' => 'lc',
							'sparkline' => 'ls',
							'bar-horizontal' => 'bhg',
							'bar-vertical' => 'bvg',
						);

	protected $type;
	protected $title;
	protected $data = array();
	protected $size = array();
	protected $color = array();
	protected $fill = array();
	protected $labelsXY = false;
	protected $legend;
	protected $useLegend = true;
	protected $background = 'a,s,ffffff';
	protected $max;
	protected $min;

	protected $query = array();

	// debug
	public $debug = array();

	// Return string
	public function __toString()
	{
		return $this->display();
	}


	/** Create chart
	*/
	protected function display()
	{
		// Обработка данных для формирования запроса
		$this->query = array(
							'cht'	 => $this->types[strtolower($this->type)],					// Type
							'chtt'	 => $this->title,											// Title
							'chd' => 't:'.$this->data['values'],								// Data
							'chl'   => $this->data['names'],									// Data labels
							'chdl' => ( ($this->useLegend) && (is_array($this->legend)) ) ? implode('|',$this->legend) : null, // Data legend
							'chds' => '0,'.$this->max,						// Data scale */
							'chs'   => $this->size[0].'x'.$this->size[1],						// Size
							'chco'   => preg_replace( '/[#]+/', '', implode(',',$this->color)), // Color ( Remove # from string )
							/* 'chm'   => preg_replace( '/[#]+/', '', implode('|',$this->fill)),        v oblom nastraivat'      // Fill ( Remove # from string ) */
							'chxt' => ( $this->labelsXY == true) ? 'y' : null,				// X & Y axis labels
							//'chxr' => '0,10,30',												// Data range
							'chxr' => '0,0,'.$this->max, 						// Data range
							'chf' => preg_replace( '/[#]+/', '', $this->background),			// Background color ( Remove # from string )
						);

		// Return chart
		return $this->img(
					GoogChart::BASE.http_build_query($this->query),
					$this->title
				);
	}

	/** Set attributes
	*/
	public function setChartAttrs( $attrs )
	{
		// debug
		$this->debug[] = $attrs;

		foreach( $attrs as $key => $value )
		{
			$this->{"set$key"}($value);
		}
	}

	/** Set type
	*/
	protected function setType( $type )
	{
		$this->type = $type;
	}


	/** Set title
	*/
	protected function setTitle( $title )
	{
		$this->title = $title;
	}


	/** Set data
	*/
	protected function setData( $data )
	{
		// Clear any previous data
		unset( $this->data );

		// Check if multiple data
		if( is_array(reset($data)) )
		{
			/** Multiple sets of data
			*/
			foreach( $data as $key => $value )
			{
				// Set highest & lowest
				foreach( $value as $valueS ) {
					if( empty($this->min) || $valueS < $this->min )
						$this->min = $valueS;
					if( empty($this->max) || $valueS > $this->max )
						$this->max = $valueS;
				}
				// Add data values
				$this->data['values'][] = implode( ',', $value );

				// Add data names
				$this->data['names'] = implode( '|', array_keys( $value ) );
			}
			/** Implode data correctly
			*/
			$this->data['values'] = implode('|', $this->data['values']);
			/** Create legend
			*/
			$this->legend = array_keys( $data );
		}
		else
		{
			// Set highest & lowest
			foreach( $data as $value ) {
				if( empty($this->min) || $value < $this->min )
					$this->min = $value;
				if( empty($this->max) || $value > $this->max )
					$this->max = $value;
			}
			/** Single set of data
			*/
			// Add data values
			$this->data['values'] = implode( ',', $data );

			// Add data names
			$this->data['names'] = implode( '|', array_keys( $data ) );
		}

	}

	/** Set legend
	*/
	protected function setLegend( $legend )
	{
		$this->useLegend = $legend;
	}

	/** Set size
	*/
	protected function setSize( $width, $height = null )
	{
		// check if width contains multiple params
		if(is_array( $width ) )
		{
			$this->size = $width;
		}
		else
		{
			// set each individually
			$this->size[] = $width;
			$this->size[] = $height;
		}
	}

	/** Set color
	*/
	protected function setColor( $color )
	{
		$this->color = $color;
	}

	/** Set labels
	*/
	protected function setLabelsXY( $labels )
	{
		$this->labelsXY = $labels;
	}

	/** Set fill
	*/
	protected function setFill( $fill )
	{
		// Fill must have atleast 4 parameters
		if( count( $fill ) < 4 )
		{
			// Add remaining params
			$count = count( $fill );
			for( $i = 0; $i < $count; ++$i )
				$fill[$i] = 'b,'.$fill[$i].','.$i.','.($i+1).',0';
		}
		
		$this->fill = $fill;
	}


	/** Set background
	*/
	protected function setBackground( $background )
	{
		$this->background = 'bg,s,'.$background;
	}

	/** Create img html tag
	*/
	protected function img( $url, $alt = null )
	{
		return sprintf('<img src="%s" alt="%s" style="width:%spx;height:%spx;" />', $url, $alt, $this->size[0], $this->size[1]);
	}


}